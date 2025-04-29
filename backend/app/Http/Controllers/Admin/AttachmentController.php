<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttachmentRequest;
use App\Models\Attachment;
use App\Models\Child;
use App\Models\ChildDiagnose;
use App\Models\ChildProgram;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AttachmentController extends Controller
{
    public function childAttachments(Child $child)
    {
        return Response::success($child->attachments);
    }

    public function childPlans(Child $child)
    {
        $plans = Attachment::query()
            ->where('attachable_type', '=', 'ChildPlan')
            ->where('attachable_id', '=', $child->id)
            ->get();
        return Response::success($plans);
    }

    public function programAttachments(Program $program)
    {
        return Response::success($program->attachments);
    }

    public function childrenProgramAttachments(ChildProgram $childProgram)
    {
        return Response::success($childProgram->attachments);
    }

    public function store(AttachmentRequest $request)
    {
        $input = $request->validated();
        $input['attachable_type'] = match ($input['attachable_type']) {
            'child' => Child::class,
            'program' => Program::class,
            'child_program' => ChildProgram::class,
            'child_diagnose' => ChildDiagnose::class,
            'child_plan' => 'ChildPlan',
            default => $input['attachable_type']
        };
        $attachment = Attachment::create($input);
        return Response::success($attachment);
    }

    public function update(AttachmentRequest $request, Attachment $attachment)
    {
        $input = $request->validated();
        $attachment->update($input);
        return Response::success($attachment);
    }

    public function destroy(Attachment $attachment)
    {
        return Response::success($attachment->delete());
    }

    public function show(Attachment $attachment)
    {
        return Response::success($attachment);
    }
}
