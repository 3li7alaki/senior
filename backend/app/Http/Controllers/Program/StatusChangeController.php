<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Http\Requests\Program\StatusChangeRequest;
use App\Models\ChildProgram;
use App\Models\StatusChange;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use PhpParser\Node\Expr\Array_;

class StatusChangeController extends Controller
{
    public function index(ChildProgram $childProgram)
    {
        return Response::success($childProgram->statusChanges()->get());
    }

    public function store(StatusChangeRequest $request, ChildProgram $childProgram)
    {
        $input = array_merge($request->validated(), ['user_id' => Auth::id()]);
        return Response::success($childProgram->statusChanges()->create($input));
    }

    public function update(StatusChangeRequest $request, ChildProgram $childProgram, StatusChange $statusChange)
    {
        $input = array_merge($request->validated(), ['user_id' => Auth::id()]);
        $statusChange->update($input);
        return Response::success($statusChange->load(StatusChange::RELATIONS));
    }

    public function destroy(ChildProgram $childProgram, StatusChange $statusChange)
    {
        return Response::success($statusChange->delete());
    }

    public function show(ChildProgram $childProgram, StatusChange $statusChange)
    {
        return Response::success($statusChange->load(StatusChange::RELATIONS));
    }
}
