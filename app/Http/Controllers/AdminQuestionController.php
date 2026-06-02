<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhishingScenario; 
use App\Models\MalwareScenario;
use App\Models\Question;

class AdminQuestionController extends Controller
{
    // READ: Show all scenarios on the main dashboard (Fixes the 500 error)
    public function index()
    {
        // Fetch all scenarios from their respective separated tables
        $phishingScenarios = PhishingScenario::all();
        $malwareScenarios = MalwareScenario::all();
        $Question = Question::all();

        // Pass all three collections to the view
        return view('admin.questions.index', compact('phishingScenarios', 'malwareScenarios', 'Question'));
    }

    // CREATE: Show the dynamic form
    public function create()
    {
        return view('admin.questions.create');
    }

    // STORE: Save data correctly based on the module type
    public function store(Request $request)
    {
        $module = $request->input('module_type');

        if ($module === 'phishing') {
            PhishingScenario::create([
                'key'            => $request->key,
                'title'          => $request->title,
                'type'           => $request->type,
                'sender_name'    => $request->sender_name,
                'sender_email'   => $request->sender_email,
                'subject'        => $request->subject,
                'body'           => $request->body,
                'is_phishing'    => $request->is_phishing,
                'malicious_zone' => $request->malicious_zone,
                'feedback'       => $request->feedback,
            ]);
            $msg = 'Phishing Scenario Created!';
        } 
        elseif ($module === 'malware') {
            $options = [
                ['text' => $request->opt1_text, 'result' => $request->opt1_result, 'feedback' => $request->opt1_feedback],
                ['text' => $request->opt2_text, 'result' => $request->opt2_result, 'feedback' => $request->opt2_feedback]
            ];

            MalwareScenario::create([
                'title'       => $request->title,
                'filename'    => $request->filename,
                'filetype'    => $request->filetype,
                'publisher'   => $request->publisher,
                'description' => $request->description,
                'options'     => json_encode($options),
            ]);
            $msg = 'Malware Scenario Created!';
        } 
        elseif ($module === 'spam') {
    $options = [
        ['text' => $request->opt1_text, 'result' => $request->opt1_result, 'feedback' => $request->opt1_feedback],
        ['text' => $request->opt2_text, 'result' => $request->opt2_result, 'feedback' => $request->opt2_feedback]
    ];

    // Using Question model here instead of SpamScenario
    Question::create([
        'module'       => 'spam',
        'type'         => $request->type, 
        'key'          => $request->key,
        'title'        => $request->title,
        'sender_name'  => $request->sender_name,
        'sender_email' => $request->sender_email,
        'subject'      => $request->subject,
        'body'         => $request->body,
        'options'      => json_encode($options),
    ]);
    $msg = 'Spam Scenario Created!';
}
        return redirect()->route('questions.index')->with('success', $msg);
    }
    
    // --- EDIT: Show the form to edit a scenario ---
    public function edit($id, Request $request)
    {
        $module = $request->query('module');

        if ($module === 'phishing') {
            $scenario = PhishingScenario::findOrFail($id);
        } elseif ($module === 'malware') {
            $scenario = MalwareScenario::findOrFail($id);
            $scenario->options = json_decode($scenario->options, true); // Decode JSON for the view
        } elseif ($module === 'spam') {
            $scenario = Question::findOrFail($id);
            $scenario->options = json_decode($scenario->options, true); // Decode JSON for the view
        } else {
            abort(404, 'Module type missing.');
        }

        return view('admin.questions.edit', compact('scenario', 'module'));
    }

    // --- UPDATE: Save the edited changes ---
    public function update(Request $request, $id)
    {
        $module = $request->input('module_type');

        if ($module === 'phishing') {
            PhishingScenario::findOrFail($id)->update([
                'key' => $request->key, 'title' => $request->title, 'type' => $request->type,
                'sender_name' => $request->sender_name, 'sender_email' => $request->sender_email,
                'subject' => $request->subject, 'body' => $request->body,
                'is_phishing' => $request->is_phishing, 'malicious_zone' => $request->malicious_zone, 'feedback' => $request->feedback,
            ]);
        } elseif ($module === 'malware') {
            $options = [
                ['text' => $request->opt1_text, 'result' => $request->opt1_result, 'feedback' => $request->opt1_feedback],
                ['text' => $request->opt2_text, 'result' => $request->opt2_result, 'feedback' => $request->opt2_feedback]
            ];
            MalwareScenario::findOrFail($id)->update([
                'title' => $request->title, 'filename' => $request->filename, 'filetype' => $request->filetype,
                'publisher' => $request->publisher, 'description' => $request->description, 'options' => json_encode($options),
            ]);
        } elseif ($module === 'spam') {
            $options = [
                ['text' => $request->opt1_text, 'result' => $request->opt1_result, 'feedback' => $request->opt1_feedback],
                ['text' => $request->opt2_text, 'result' => $request->opt2_result, 'feedback' => $request->opt2_feedback]
            ];
            Question::findOrFail($id)->update([
                'type' => $request->type, 'key' => $request->key, 'title' => $request->title,
                'sender_name' => $request->sender_name, 'sender_email' => $request->sender_email,
                'subject' => $request->subject, 'body' => $request->body, 'options' => json_encode($options),
            ]);
        }

        return redirect()->route('questions.index')->with('success', ucfirst($module) . ' Scenario Updated Successfully!');
    }

    // --- DESTROY: Delete the scenario ---
    public function destroy($id, Request $request)
    {
        $module = $request->input('module');

        if ($module === 'phishing') PhishingScenario::findOrFail($id)->delete();
        elseif ($module === 'malware') MalwareScenario::findOrFail($id)->delete();
        elseif ($module === 'spam') Question::findOrFail($id)->delete();

        return redirect()->route('questions.index')->with('success', 'Scenario Deleted Successfully!');
    }
}