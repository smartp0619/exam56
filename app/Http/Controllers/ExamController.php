<?php

namespace App\Http\Controllers;

use App\Exam; //使用Exam模型
use App\Http\Requests\ExamRequest; //使用Topic模型

use App\Topic; //使用ExamRequest

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user and $user->can('建立測驗')) {
            $exams = Exam::orderBy('created_at', 'desc')
                ->paginate(5);
        } else {
            $exams = Exam::where('enable', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(5);
        }
        // $topic = Topic::create($request->all());
        // return view('exam.index', ['exams' => $exams, 'topics' => $topics]);

        return view('exam.index', ['exams' => $exams]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exam.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request)
    {
        Exam::create([
            'title'   => $request->title,
            'user_id' => $request->user_id,
            'enable'  => $request->enable,
        ]);

        return redirect()->route('exam.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        $user = Auth::user();
        if ($user and $user->can('進行測驗')) {
            $exam->topics = $exam->topics->random(10);
        }

        return view('exam.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        return view('exam.create', compact('exam'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, Exam $exam)
    {
        $exam->update($request->all());
        return redirect()->route('exam.show', $exam->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(exam $exam)
    {
        $exam->delete();
        return redirect()->route('exam.index');
    }
}
