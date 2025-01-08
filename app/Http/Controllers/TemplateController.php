<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use App\Models\TemplateItem;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::when(auth()->user()->type != 'admin', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();
        return response()->json([
            'status' => 'success',
            'data' => $templates,
            'message' => 'Templates fetched successfully',
        ], 200);
    }

    public function store(TemplateRequest $request)
    {
        DB::beginTransaction();
        try {
            $template = Template::create([
                'user_id' => auth()->id(),
                'title' => $request['title'],
                'description' => $request['description'],
                'columns' => $request['columns'],
                'rows' => $request['rows'],
            ]);
            foreach ($request['charts'] as $i => $chart) {
                if (!is_null($chart)) {
                    TemplateItem::create([
                        'order' => $i,
                        'template_id' => $template->id,
                        'count' => $chart['count'],
                        'registers' => json_encode($chart['registers']),
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $template,
                'message' => 'Template stored successfully',
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'Error while storing template'
            ], 500);
        }
    }

    public function show(Template $template)
    {
        $template->load('Items');
        $template->Items->map(function($item) {
            $item->registers = $item->GetRegisters();
            $item->registers->map(function($register) use ($item) {
                $register->logs = $register->ChartLogs($item->count)->pluck('value', 'created_at');
            });
        });
        return response()->json([
            'status' => 'success',
            'data' => $template,
            'message' => 'Template fetched successfully',
        ], 200);
    }

    public function update(Template $template, TemplateRequest $request)
    {
        DB::beginTransaction();
        try {
            $template->update([
                'title' => $request['title'],
                'columns' => $request['columns'],
                'description' => $request['description'],
                'rows' => $request['rows'],
            ]);
            $template->Items->each->delete();
            foreach ($request['charts'] as $i => $chart) {
                if (!is_null($chart)) {
                    TemplateItem::create([
                        'order' => $i,
                        'template_id' => $template->id,
                        'count' => $chart['count'],
                        'registers' => json_encode($chart['registers']),
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $template,
                'message' => 'Template updated successfully',
            ], 200);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'Error while updating template'
            ], 500);
        }
    }

    public function destroy(Template $template)
    {
        DB::beginTransaction();
        try {
            $template->Items->each->delete();
            $template->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Template deleted successfully',
            ], 200);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'data' => $exception->getMessage(),
                'message' => 'Error while deleting template',
            ], 500);
        }
    }
}
