<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Support\SafeText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function listing()
    {
        $articles = Article::published()
            ->with(['user.companyProfile', 'user.category'])
            ->latest('published_at')
            ->latest('created_at')
            ->paginate(12);

        return view('front.pages.articles', compact('articles'));
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $this->resolvePerPage($request);

        $articles = $user->articles()
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        $stats = [
            'total' => $user->articles()->count(),
            'published' => $user->articles()->where('status', 'published')->count(),
            'drafts' => $user->articles()->where('status', 'draft')->count(),
        ];

        return view('front.users.articles', compact('articles', 'stats'));
    }

    public function show(string $slug)
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with(['user.companyProfile', 'user.category'])
            ->firstOrFail();

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->with(['user.companyProfile', 'user.category'])
            ->latest('published_at')
            ->take(3)
            ->get();

        $author = $article->user;
        $company = $author?->companyProfile;
        $authorName = $company?->company_name
            ?: trim(($author?->fname ?? '').' '.($author?->lname ?? ''))
            ?: 'JustGoom Member';
        $wordCount = str_word_count(strip_tags($article->body));
        $readMinutes = max(1, (int) ceil($wordCount / 200));

        return view('front.pages.article-detail', compact(
            'article',
            'relatedArticles',
            'author',
            'company',
            'authorName',
            'readMinutes'
        ));
    }

    public function create()
    {
        return view('front.users.article-form', ['article' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:300', SafeText::titleRule()],
            'body' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ], [
            'title.regex' => SafeText::titleMessage('Article title'),
        ]);

        $article = new Article();
        $article->user_id = $request->user()->id;
        $article->title = $validated['title'];
        $article->slug = Article::generateSlug($validated['title']);
        $article->body = $validated['body'];
        $article->status = $validated['status'];

        if ($validated['status'] === 'published') {
            $article->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $article->featured_image = $this->uploadFile($request->file('featured_image'), 'articles');
        }

        $article->save();

        return redirect()->route('front.users.articles')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $this->authorizeArticle($article);

        return view('front.users.article-form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:300', SafeText::titleRule()],
            'body' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ], [
            'title.regex' => SafeText::titleMessage('Article title'),
        ]);

        $article->title = $validated['title'];
        $article->body = $validated['body'];
        $article->status = $validated['status'];

        if ($validated['status'] === 'published' && !$article->published_at) {
            $article->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            $this->deleteFile($article->featured_image);
            $article->featured_image = $this->uploadFile($request->file('featured_image'), 'articles');
        }

        $article->save();

        return redirect()->route('front.users.articles')
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $this->authorizeArticle($article);

        $this->deleteFile($article->featured_image);

        $article->delete();

        return redirect()->route('front.users.articles')
            ->with('success', 'Article deleted successfully.');
    }

    public function updateStatus(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $validated = $request->validate([
            'status' => ['required', 'in:draft,published'],
        ]);

        $article->status = $validated['status'];
        if ($validated['status'] === 'published' && ! $article->published_at) {
            $article->published_at = now();
        }
        $article->save();

        return back()->with('success', 'Article status updated.');
    }

    private function authorizeArticle(Article $article): void
    {
        abort_unless((int) $article->user_id === (int) auth()->id(), 403);
    }

    private function resolvePerPage(Request $request): int
    {
        $perPage = (int) $request->query('per_page', 10);

        return in_array($perPage, [10, 25, 50], true) ? $perPage : 10;
    }

    private function uploadFile($file, string $subfolder): string
    {
        $email = auth()->user()->email;
        $destination = public_path('uploads/' . $email . '/' . $subfolder);
        if (! File::isDirectory($destination)) {
            File::makeDirectory($destination, 0777, true);
        }

        $filename = time() . '-' . Str::random(12) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'uploads/' . $email . '/' . $subfolder . '/' . $filename;
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
