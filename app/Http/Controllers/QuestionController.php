<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Display a listing of questions for a specific category
    public function index(Category $category)
    {
        $questions = $category->questions;
        return view('admin.questions.index', compact('category', 'questions'));
    }

    // Show the form for creating a new question
    public function create(Category $category)
    {
        return view('admin.questions.create', compact('category'));
    }

    // Store a newly created question in the database

    public function store(Request $request)
    {
        // Log incoming request data
        \Log::info('Incoming request data:', $request->all());

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.is_required' => 'required|boolean',
        ]);

        // Create the category
        $category = Category::create([
            'name' => $validatedData['name'],
        ]);

        // Check if questions exist and are an array
        if (isset($validatedData['questions']) && is_array($validatedData['questions'])) {
            foreach ($validatedData['questions'] as $questionData) {
                // Ensure question data is valid before creating
                if (!empty($questionData['question']) && !empty($questionData['type'])) {
                    Question::create([
                        'category_id' => $category->id,
                        'question' => $questionData['question'],
                        'type' => $questionData['type'],
                        'is_required' => $questionData['is_required'],
                    ]);
                }
            }
        } else {
            \Log::error('No questions were provided or questions is not an array.');
        }

        // Return response
        return response()->json(['success' => true, 'category_id' => $category->id]);
    }


    // Show the form for editing an existing question
    public function edit(Category $category, Question $question)
    {
        return view('admin.questions.edit', compact('category', 'question'));
    }

    // Update the specified question in the database
    public function update(Request $request, Category $category, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string|max:255',
            'type' => 'required|string|in:text,date,select',
            'is_required' => 'required|boolean',
        ]);

        $question->update($request->only(['question_text', 'type', 'is_required']));

        return redirect()->route('admin.questions.index', $category)->with('success', 'Question updated successfully.');
    }

    // Remove the specified question from the database
    public function destroy(Category $category, Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index', $category)->with('success', 'Question deleted successfully.');
    }
}
