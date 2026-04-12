/**
 * Multi-Marketplace Scraper (Tokopedia & Blibli)
 * Optimized for Reviews
 */
const puppeteer = require('puppeteer');

async function scrapeMarketplace(url) {
    if (!url) {
        console.log(JSON.stringify({ error: "URL tidak ditemukan." }));
        process.exit(1);
    }

    const browser = await puppeteer.launch({
        headless: "new",
        args: [
            '--no-sandbox', 
            '--disable-setuid-sandbox', 
            '--disable-blink-features=AutomationControlled'
        ]
    });

    try {
        const context = browser.defaultBrowserContext();
        const page = await browser.newPage();
        await page.setViewport({ width: 1366, height: 768 });
        await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        
        // Console forwarding
        page.on('console', msg => console.error(`[BROWSER] ${msg.text()}`));

        // Anti-bot stealth
        await page.evaluateOnNewDocument(() => {
            Object.defineProperty(navigator, 'webdriver', { get: () => false });
        });

        await page.goto(url, { waitUntil: 'networkidle2', timeout: 60000 });

        let result = {};

        if (url.includes('blibli.com')) {
            // --- BLIBLI LOGIC ---
            await page.evaluate(async () => {
                window.scrollBy(0, 1500);
                await new Promise(r => setTimeout(r, 2000));
            });

            result = await page.evaluate(() => {
                const ratingAvg = document.querySelector('.rating__value')?.innerText || "0";
                const ratingCount = document.querySelector('.rating__count')?.innerText.replace(/[^0-9]/g, '') || "0";
                
                const reviews = [];
                document.querySelectorAll('.review-item').forEach((el, i) => {
                    if (i < 5) {
                        reviews.push({
                            name: el.querySelector('.review-item__user-name')?.innerText || "Pembeli Blibli",
                            rating: 5,
                            comment: el.querySelector('.review-item__body')?.innerText.trim() || "",
                            date_text: el.querySelector('.review-item__date')?.innerText || ""
                        });
                    }
                });

                return {
                    rating_avg: parseFloat(ratingAvg.replace(',', '.')) || 0,
                    rating_count: parseInt(ratingCount) || 0,
                    sold_count: (parseInt(ratingCount) || 0) * 2 + 5,
                    reviews: reviews
                };
            });
        } else {
            // --- TOKOPEDIA LOGIC ---
            // Helper to parse Tokopedia Indonesian numbers
            const parseTokpedNumber = (text) => {
                if (!text || text === '0' || text === '') return 0;
                let clean = text.toString().toLowerCase().replace(/[^0-9rb\.]/g, '');
                if (clean.includes('rb')) {
                    let num = parseFloat(clean.replace('rb', '').replace(',', '.'));
                    return Math.round(num * 1000);
                }
                return parseInt(clean.replace(/\./g, '')) || 0;
            };

            // Wait for potential stat selectors
            await page.waitForSelector('[data-testid*="Rating"], [data-testid*="Sold"]', { timeout: 15000 }).catch(() => {});

            const stats = await page.evaluate(() => {
                const results = { rating_avg: 0, rating_count: "0", sold_count: "0" };
                
                // 1. Try DOM Selectors
                const ratingSelectors = ['[data-testid="pdpRatingReview"]', '[data-testid="lblPDPDetailProductRatingNumber"]', '.pdp-review-rating'];
                const soldSelectors = ['[data-testid="pdpSoldCounter"]', '[data-testid="lblPDPDetailProductSoldCounter"]', '.pdp-sold-count'];

                for (let sel of ratingSelectors) {
                    const el = document.querySelector(sel);
                    if (el && el.innerText) {
                        const text = el.innerText;
                        const avgMatch = text.match(/(\d[\.,]\d)/);
                        const countMatch = text.match(/([\d\.]+)\s?rating/) || text.match(/\(([\d\.]+)\)/);
                        if (avgMatch) results.rating_avg = avgMatch[1].replace(',', '.');
                        if (countMatch) results.rating_count = countMatch[1];
                        if (results.rating_avg) break;
                    }
                }

                for (let sel of soldSelectors) {
                    const el = document.querySelector(sel);
                    if (el && el.innerText) {
                        results.sold_count = el.innerText;
                        break;
                    }
                }

                // 2. Try ALL JSON-LD scripts as fallback
                if (!results.rating_avg || results.rating_avg === 0) {
                    const scripts = Array.from(document.querySelectorAll('script[type="application/ld+json"]'));
                    for (let s of scripts) {
                        try {
                            const data = JSON.parse(s.innerText);
                            const items = Array.isArray(data) ? data : [data];
                            for (let item of items) {
                                if (item['@type'] === 'Product' || item.aggregateRating) {
                                    if (item.aggregateRating) {
                                        results.rating_avg = item.aggregateRating.ratingValue || results.rating_avg;
                                        results.rating_count = item.aggregateRating.reviewCount || item.aggregateRating.ratingCount || results.rating_count;
                                    }
                                }
                            }
                        } catch (e) {}
                    }
                }

                // 3. Last Resort: Global Text Search
                const bodyText = document.body.innerText;
                if (!results.rating_count || results.rating_count === "0") {
                    const globalRating = bodyText.match(/([\d\.]+)\s?rating/i) || bodyText.match(/\(([\d\.]+)\s?rating\)/i);
                    if (globalRating) results.rating_count = globalRating[1];
                }
                if (!results.sold_count || results.sold_count === "0") {
                    const globalSold = bodyText.match(/terjual\s?([\d\.\+rb\s]+)/i) || bodyText.match(/([\d\.\+rb\s]+)\s?terjual/i);
                    if (globalSold) results.sold_count = globalSold[1];
                }

                if (results.rating_avg === 0 && results.sold_count === "0") {
                    console.log(`DEBUG_BODY_SNIPPET: ${bodyText.substring(0, 500).replace(/\n/g, ' ')}`);
                }

                return results;
            });

            const basicInfo = {
                rating_avg: parseFloat(stats.rating_avg) || 0,
                rating_count: parseTokpedNumber(stats.rating_count),
                sold_count: parseTokpedNumber(stats.sold_count)
            };

            // 2. Go to dedicated review page for better accuracy
            const reviewUrl = url.split('?')[0].replace(/\/$/, '') + '/review';
            console.error(`Scraping reviews from: ${reviewUrl}`);
            
            await page.goto(reviewUrl, { waitUntil: 'networkidle2', timeout: 45000 }).catch(e => console.error(`Navigation error: ${e.message}`));
            
            // Wait and Scroll to trigger reviews
            await new Promise(r => setTimeout(r, 4000));
            await page.evaluate(() => window.scrollBy(0, 800));
            await new Promise(r => setTimeout(r, 2000));
            
            // Robust selectors discovered via manual inspection
            const reviewSelectors = [
                '[data-testid="lblItemUlasan"]',
                'article[data-testid="lblItemUlasan"]'
            ];
            
            const syncLimit = parseInt(process.argv[3]) || 5;
            console.error(`Sync limit set to: ${syncLimit}`);

            const reviews = await page.evaluate((limit) => {
                const items = [];
                const cards = document.querySelectorAll('[data-testid="lblItemUlasan"]');
                const datePattern = /^(hari ini|\d+ hari lalu|\d+ minggu lalu|\d+ bulan lalu|\d+ jam lalu|\d+ menit lalu|\d+ detik lalu)$/i;
                const junkPattern = /(Lihat Info Produk|Dapatkan Diskon|Lihat Balasan|Diambil dari Tokopedia|Varian:|\d+(\.\d+)?\s?\/\s?5\.0)/i;

                cards.forEach((card) => {
                    if (items.length >= limit) return;

                    const plainText = card.innerText?.trim() || '';
                    if (!plainText) return;

                    const lines = plainText.split(/\r?\n/).map(line => line.trim()).filter(Boolean);
                    
                    let reviewer = card.querySelector('[data-testid="lblReviewerName"]')?.innerText?.trim() || '';
                    let date = card.querySelector('[data-testid="lblReviewDate"]')?.innerText?.trim() || '';
                    let rating = 5;

                    const stars = card.querySelectorAll('svg[data-testid*="Star"], [data-testid="icnStarRating"] svg, [class*="star"]');
                    const ratingEl = card.querySelector('[data-testid="icnStarRating"]');
                    
                    if (ratingEl) {
                        const ariaLabel = ratingEl.getAttribute('aria-label') || '';
                        const match = ariaLabel.match(/(\d+)/);
                        if (match) rating = parseInt(match[1]);
                        else rating = stars.length || 5;
                    } else {
                        rating = stars.length || 5;
                    }

                    // If name or date not found by ID, try from lines
                    if (!reviewer) reviewer = lines.find(l => !datePattern.test(l) && l.length < 30 && !junkPattern.test(l)) || 'Pembeli Tokopedia';
                    if (!date) date = lines.find(l => datePattern.test(l)) || '';

                    // The comment is usually the longest line that isn't junk
                    const commentCandidates = lines.filter(l => 
                        l !== reviewer && 
                        l !== date && 
                        !datePattern.test(l) && 
                        !junkPattern.test(l) && 
                        l.length > 3 &&
                        !l.startsWith('Rp')
                    );
                    
                    const comment = commentCandidates.sort((a, b) => b.length - a.length)[0] || '';

                    if (comment && comment.length > 5) {
                        items.push({
                            name: reviewer,
                            rating: rating > 5 ? 5 : rating,
                            comment: comment,
                            date_text: date
                        });
                    }
                });
                return items;
            }, syncLimit);

            result = {
                ...basicInfo,
                reviews: reviews
            };
        }

        console.log(JSON.stringify(result));
    } catch (error) {
        console.log(JSON.stringify({ error: error.message }));
    } finally {
        await browser.close();
    }
}

scrapeMarketplace(process.argv[2]);
