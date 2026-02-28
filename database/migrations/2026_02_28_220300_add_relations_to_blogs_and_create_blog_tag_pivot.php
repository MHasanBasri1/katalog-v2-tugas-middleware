<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table): void {
            $table->foreignId('category_id')->nullable()->after('cover_image')->constrained('blog_categories')->nullOnDelete();
            $table->foreignId('author_user_id')->nullable()->after('author_name')->constrained('users')->nullOnDelete();
        });

        Schema::create('blog_blog_tag', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->cascadeOnDelete();
            $table->foreignId('blog_tag_id')->constrained('blog_tags')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['blog_id', 'blog_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_blog_tag');

        Schema::table('blogs', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('category_id');
            $table->dropConstrainedForeignId('author_user_id');
        });
    }
};
