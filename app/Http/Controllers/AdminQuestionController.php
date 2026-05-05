<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
    // 1. LIST QUESTIONS
    public function index()
    {
        $questions = Question::where('module', 'phishing')->get();
        return view('admin.questions.index', compact('questions'));
    }

    // 2. SHOW CREATE FORM
    public function create()
    {
        return view('admin.questions.create');
    }

    // 3. STORE NEW QUESTION
    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|unique:questions,key',
            'title' => 'required',
            'type' => 'required|in:email,sms',
            'sender_name' => 'required',
            'sender_email' => 'nullable',
            'subject' => 'nullable',
            'body' => 'required',
            'options' => 'required|array', // Expecting array of options
        ]);

        $data['module'] = 'phishing';
        Question::create($data);

        return redirect()->route('admin.questions.index')->with('success', 'Scenario created successfully!');
    }

    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    // 5. UPDATE QUESTION
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        
        $data = $request->validate([
            'title' => 'required',
            'type' => 'required|in:email,sms',
            'sender_name' => 'required',
            'sender_email' => 'nullable',
            'subject' => 'nullable',
            'body' => 'required',
            'options' => 'required|array',
        ]);

        $question->update($data);

        return redirect()->route('admin.questions.index')->with('success', 'Scenario updated successfully!');
    }

    // 6. DELETE QUESTION
    public function destroy($id)
    {
        Question::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Scenario deleted.');
    }
}