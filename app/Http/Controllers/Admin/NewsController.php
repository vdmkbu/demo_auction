<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = Post::orderByDate()->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        // валидация
        $this->validate($request, [
           'name' => 'required|string|max:255',
           'date' => 'required|date_format:Y-m-d H:i',
           'announce' => 'max:150',
           'text' => 'required|max:3000',
           'files.*' => 'mimes:jpg,png,jpeg'
        ]);

        // массив объектов
        $files = $request->file('files');

        $news = new Post();
        $news->name = $request->name;
        $news->date = $request->date;
        $news->announce = $request->announce;
        $news->text = $request->text;
        $news->user_id = auth()->id();
        $news->save();



        if ($files) {

            $images = [];
            foreach ($files as $file) {
                $path = $file->store('public/images');
                $images[] = new Image([
                    'path' => $path,
                    'news_id' => $news->id
                ]);

            }

            $news->images()->saveMany($images);

        }


        return redirect(route('admin.news.index'))->with('success', 'Новость добавлена');

    }

    public function edit(Post $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Post $news, Request $request)
    {

        // валидация
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d H:i:s',
            'announce' => 'max:150',
            'text' => 'required|max:3000',
            'files.*' => 'mimes:jpg,png,jpeg'
        ]);

        // массив объектов
        $files = $request->file('files');

        $news->update([
           'name' => $request->name,
           'date' => $request->date,
           'announce' => $request->announce,
           'text' => $request->text
        ]);

        if ($files) {

            $images = [];
            foreach ($files as $file) {
                $path = $file->store('public/images');
                $images[] = new Image([
                    'path' => $path,
                    'news_id' => $news->id
                ]);

            }

            $news->images()->saveMany($images);

        }

        return redirect(route('admin.news.index'))->with('success', 'Новость обновлена');
    }

    public function destroy(Post $news)
    {
        $news->delete();
        return redirect('admin.news.index')->with('success', 'Новость удалена');
    }
}
