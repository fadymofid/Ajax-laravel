@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create New Category</h1>

        {{-- Display success message if available --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Display error messages --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form to create a new category with associated questions --}}
        <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            <div class="form-group">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div id="questionsContainer">
                <h4>Questions</h4>
                {{-- Initial question item --}}
                <div class="question-item">
                    <div class="form-group">
                        <label for="question_text">Question Text</label>
                        <input type="text" name="questions[0][question]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select name="questions[0][type]" class="form-control" required>
                            <option value="text">Text</option>
                            <option value="date">Date</option>
                            <option value="select">Select</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="is_required">Is Required</label>
                        <select name="questions[0][is_required]" class="form-control" required>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Button to add another question --}}
            <button type="button" id="addQuestion" class="btn btn-primary">Add Another Question</button>
            <button type="submit" class="btn btn-success">Create Category</button>
        </form>
    </div>

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                let questionIndex = 1; // Index for tracking question numbers

                // Event listener for adding new questions
                $('#addQuestion').click(function() {
                    // Create a new question item dynamically
                    let newQuestion = `
                        <div class="question-item mt-3">
                            <div class="form-group">
                                <label for="question_${questionIndex}">Question Text</label>
                                <input type="text" name="questions[${questionIndex}][question]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="type_${questionIndex}">Type</label>
                                <select name="questions[${questionIndex}][type]" class="form-control" required>
                                    <option value="text">Text</option>
                                    <option value="date">Date</option>
                                    <option value="select">Select</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="is_required_${questionIndex}">Is Required</label>
                                <select name="questions[${questionIndex}][is_required]" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    `;
                    // Append the new question to the questions container
                    $('#questionsContainer').append(newQuestion);
                    questionIndex++; // Increment the question index
                });

                // Handle form submission
                $('#categoryForm').submit(function(e) {
                    e.preventDefault(); // Prevent the default form submission
                    const formData = $(this).serialize(); // Serialize the form data

                    // Submit the category data
                    $.ajax({
                        url: $(this).attr('action'), // Get the action URL from the form
                        method: 'POST',
                        data: formData, // Send the serialized form data
                        success: function(response) {
                            if (response.success) {
                                const categoryId = response.category_id; // Store the category ID for questions

                                // Prepare questions data for submission
                                const questionsData = $(this).find('input, select').serializeArray()
                                    .filter(item => item.name.startsWith('questions'));

                                // Send questions to the QuestionController
                                $.ajax({
                                    url: `/admin/questions/store/${categoryId}`, // Dynamic URL for storing questions
                                    method: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}', // Include CSRF token for security
                                        questions: questionsData // Send the questions data
                                    },
                                    success: function() {
                                        // Redirect to the categories index after successful submission
                                        window.location.href = '{{ route('admin.categories.index') }}';
                                    },
                                    error: function(xhr) {
                                        console.error('Error saving questions:', xhr);
                                        alert('Error saving questions: ' + xhr.responseJSON.message || 'Check the console for more details.');
                                    }
                                });
                            } else {
                                console.error('Error saving category:', response);
                                alert('Error saving category: ' + response.message || 'Check the console for more details.');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error saving category:', xhr);
                            alert('Error saving category: ' + xhr.responseJSON.message || 'Check the console for more details.');
                        }
                    });
                });
            });
        </script>
    @endsection
@endsection
