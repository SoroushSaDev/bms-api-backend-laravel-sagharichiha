<?php

namespace App\Http\Controllers;

use App\Http\Requests\TranslationRequest;
use App\Models\Translation;
use DB;

class TranslationController extends Controller
{
    public function index()
    {
        $translations = Translation::paginate(10);
        return view('translations.index', compact('translations'));
    }

    public function create()
    {
        $languages = Translation::Languages;
        return view('translations.create', compact('languages'));
    }

    public function store(TranslationRequest $request)
    {
        DB::beginTransaction();
        try {
            Translation::create([
                'key' => $request['key'],
                'lang' => $request['lang'],
                'value' => $request['value'],
            ]);
            DB::commit();
            return redirect(route('translations.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function show(Translation $translation)
    {
        return view('translations.show', compact('translation'));
    }

    public function edit(Translation $translation)
    {
        $languages = Translation::Languages;
        return view('translations.edit', compact('translation', 'languages'));
    }

    public function update(TranslationRequest $request, Translation $translation)
    {
        DB::beginTransaction();
        try {
            $translation->update([
                'key' => $request['key'],
                'lang' => $request['lang'],
                'value' => $request['value'],
            ]);
            DB::commit();
            return redirect(route('translations.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }

    public function destroy(Translation $translation)
    {
        DB::beginTransaction();
        try {
            $translation->delete();
            DB::commit();
            return redirect(route('translations.index'));
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception);
        }
    }
}
