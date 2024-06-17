@extends('admin-layout')

@section('content')

<div class="container-fluid">

    <!-- Tabs navigation -->
    <ul class="nav nav-tabs" id="quizTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="page-tab" data-bs-toggle="tab" data-bs-target="#page" type="button"
                role="tab" aria-controls="page" aria-selected="true">Page</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="quiz-tab" data-bs-toggle="tab" data-bs-target="#quiz" type="button" role="tab"
                aria-controls="quiz" aria-selected="false">Quiz</button>
        </li>
    </ul>
    <div class="tab-content" id="quizTabsContent">
        <!-- Add Page -->
        <div class="tab-pane fade show active" id="page" role="tabpanel" aria-labelledby="page-tab">
            <div style="padding-bottom: 2rem;">
                <h1>Create New Page</h1>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <x-head.tinymce-config />

            <form
                action="{{ route('admin.add_page.post', ['chapterId' => $chapterId]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title-container">
                            <h5><label for="title" class="form-label page-title">Page Title:</label></h5>
                        </div>
                        <div class="page-content">
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Enter Page Title" required>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title-container">
                            <h5><label for="description" class="form-label page-title">Page Description:</label></h5>
                        </div>
                        <div class="page-content">
                            <textarea class="form-control tinymce" id="description" name="description" rows="10"
                                placeholder="Enter description"></textarea>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title-container">
                            <h6><label for="content" class="form-label page-title">Page Content:</label></h6>
                        </div>
                        <div class="page-content">
                            <h5><label for="pdf" class="form-label">Extract Content from PDF: (Optional)</label></h5>
                            <div class="row mb-4">
                                <div class="col">
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="pdf" name="pdf"
                                            accept="application/pdf">
                                        <button type="button" class="btn btn-secondary" id="uploadPdfButton">Upload
                                            PDF</button>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control tinymce" id="content" name="content" rows="10"
                                placeholder="Enter content"></textarea>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title-container">
                            <h5><label for="pdfAttachments" class="form-label page-title">PDF Attachments:</label></h5>
                        </div>
                        <div class="page-content" id="pdfAttachmentsContainer">
                            <div class="pdf-attachment d-flex align-items-center mb-2">
                                <input type="file" class="form-control pdf-attachment-input" name="pdfAttachments[]"
                                    accept="application/pdf">
                                <button type="button" class="btn btn-danger ms-2 remove-attachment-btn">Remove</button>
                            </div>
                            <button type="button" class="btn btn-secondary mt-2" id="addAttachmentButton">Add another
                                file</button>
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary float-end marg-btm-cus">Add Page</button>
            </form>

        </div>

        <!-- Quiz Tab -->
        <div class="tab-pane fade" id="quiz" role="tabpanel" aria-labelledby="quiz-tab">

            <div style="padding-bottom: 2rem;">
                <h1>Create New Quiz</h1>
            </div>
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <form
                action="{{ route('admin.create_quiz.post', ['chapterId' => $chapterId]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title-container">
                            <h5><label for="title" class="form-label page-title">Quiz Title:</label></h5>
                        </div>
                        <div class="page-content">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title"
                                required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="page-title-container">
                            <h5><label for="passing_score" class="form-label page-title">Minimum Passing Score:</label>
                            </h5>
                        </div>
                        <div class="page-content">
                            <input type="number" class="form-control" id="passing_score" name="passing_score"
                                placeholder="Enter Passing Score" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="page-title-container">
                        <h5><label for="content" class="form-label page-title">Quiz Questions:</label></h5>
                    </div>
                    <div class="page-content">
                        <div id="question-fields"></div>
                        <button type="button" class="btn btn-success" onclick="addQuestionField()">Add Question</button>
                    </div>
                </div>

                <button type="submit" class="confirm-quiz-button float-end marg-btm-cus">Create Quiz</button>
            </form>

        </div>



    </div>
</div>




<style>
    /* .btn-success {
      min-width: 120px;
      text-align: center.
    }

    .btn-danger {
      margin-right: 10px.
    } */

    .consistent-width-input {
        width: calc(100% - 180px);
        max-width: 540px;
        display: inline-block;
    }

    .input-group .add-option-btn {
        margin-left: 10px !important;
        border-radius: 10px !important;
        height: 38px;
    }

    .form-check-input {
        transform: scale(1.1);
        /* Adjust scale to make checkbox smaller */
        margin-right: 10px;
        position: relative;
        border: 1px solid #b8bdc2;
        /* Set the checkbox border color */
    }

    .input-group {
        align-items: center;
    }

</style>

<script>
    function showErrorModal(message) {
        // Update the modal content
        document.getElementById('errorModalMessage').textContent = message;
        // Show the modal
        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
        errorModal.show();
    }

    document.getElementById('uploadPdfButton').addEventListener('click', function () {
        var formData = new FormData();
        var fileInput = document.getElementById('pdf');
        var file = fileInput.files[0];

        if (file) {
            formData.append('pdf', file);

            fetch('{{ route('admin.upload_pdf') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('pdf uploaded');
                        tinymce.get('content').setContent(data.text);
                    } else {
                        showErrorModal('Failed to upload and parse PDF');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorModal('An error occurred while uploading the PDF');
                });
        } else {
            showErrorModal('Please select a PDF file to upload');
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('addAttachmentButton').addEventListener('click', function () {
            const attachmentContainer = document.getElementById('pdfAttachmentsContainer');
            const attachmentDiv = document.createElement('div');
            attachmentDiv.classList.add('pdf-attachment', 'd-flex', 'align-items-center', 'mb-2');
            attachmentDiv.innerHTML = `
                <input type="file" class="form-control pdf-attachment-input" name="pdfAttachments[]" accept="application/pdf">
                <button type="button" class="btn btn-danger ms-2 remove-attachment-btn">Remove</button>
            `;
            attachmentContainer.insertBefore(attachmentDiv, this);

            // Add event listener to the remove button
            attachmentDiv.querySelector('.remove-attachment-btn').addEventListener('click',
                function () {
                    attachmentDiv.remove();
                });
        });

        document.querySelectorAll('.remove-attachment-btn').forEach(button => {
            button.addEventListener('click', function () {
                this.closest('.pdf-attachment').remove();
            });
        });
    });

    /*quiz script*/
    let questionCount = 0;

    function addQuestionField() {
        saveFormState();

        const questionFields = document.getElementById("question-fields");
        const currentQuestionCount = questionFields.children.length;
        const questionField = `
        <div class="mb-4 question-block" id="question-block-${currentQuestionCount}">
            <div class="input-group mb-2">
                <label for="question-${currentQuestionCount}" class="form-label" style="font-size: 15px; font-weight: bold;">Question ${currentQuestionCount + 1}:&nbsp;&nbsp;</label>
                <input type="text" class="form-control" id="question-${currentQuestionCount}" name="questions[]" placeholder="Enter question" required>
                <select class="form-control" id="question_type-${currentQuestionCount}" name="question_types[]" onchange="changeQuestionType(this)" required>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="short_answer">Text Field</option>
                    <option value="checkbox">Checkbox</option>
                </select>
                <button type="button" class="btn btn-danger" onclick="removeQuestionField(this)" style="border-radius: 10px; height: 38px; margin-left:10px;"><i class="fas fa-trash"></i></button>
            </div>
            <div id="answer-container-${currentQuestionCount}"></div>
        </div>`;
        questionFields.innerHTML += questionField;

        const questionTypeSelect = document.getElementById(`question_type-${currentQuestionCount}`);
        changeQuestionType(questionTypeSelect);

        questionCount++;

        restoreFormState();
    }

    function removeQuestionField(button) {
        const questionBlock = button.closest('.question-block');
        questionBlock.remove();
        updateQuestionNumbers();
        saveFormState();
    }

    function updateQuestionNumbers() {
        const questionBlocks = document.querySelectorAll('.question-block');
        questionBlocks.forEach((block, index) => {
            const label = block.querySelector('label');
            label.innerHTML = `Question ${index + 1}:&nbsp;&nbsp;`;
            block.id = `question-block-${index}`;
            const questionInput = block.querySelector('input[id^="question-"]');
            questionInput.id = `question-${index}`;
            const questionSelect = block.querySelector('select[id^="question_type-"]');
            questionSelect.id = `question_type-${index}`;
            questionSelect.setAttribute('onchange', `changeQuestionType(this)`);
            const answerContainer = block.querySelector('div[id^="answer-container"]');
            answerContainer.id = `answer-container-${index}`;
            updateAnswerOptionButtons(answerContainer, index);
        });
        questionCount = questionBlocks.length;
    }

    function updateAnswerOptionButtons(answerContainer, questionId) {
        const addOptionButtons = answerContainer.querySelectorAll('.btn-success');
        addOptionButtons.forEach(button => {
            button.setAttribute('onclick', `addAnswerOption(${questionId})`);
        });
        const removeButtons = answerContainer.querySelectorAll('.btn-danger');
        removeButtons.forEach(button => {
            button.setAttribute('onclick', `removeAnswerOption(this, ${questionId})`);
        });
    }

    function changeQuestionType(selectElement) {
        const questionId = selectElement.id.split('-')[1];
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        answerContainer.innerHTML = "";

        if (selectElement.value === 'multiple_choice' || selectElement.value === 'checkbox') {
            addAnswerOption(questionId);
            addAnswerOption(questionId);
        } else if (selectElement.value === 'short_answer') {
            answerContainer.innerHTML = `<div class="input-group mt-2">
                <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}" name="correct_answers[${questionId}][]" placeholder="Enter correct answer" required>
            </div>`;
        }
    }

    function addAnswerOption(questionId) {
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        const existingAnswerCount = answerContainer.querySelectorAll('.input-group').length;

        let newAnswer;
        const questionType = document.getElementById(`question_type-${questionId}`).value;
        if (questionType === 'checkbox') {
            newAnswer = `<div class="input-group mt-2">
                <input type="checkbox" class="form-check-input" name="correct_answers[${questionId}][]" value="${existingAnswerCount}">
                <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}-${existingAnswerCount}" name="answers[${questionId}][]" placeholder="Enter answer option" required>
                <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})"><i class="fas fa-trash"></i></button>
            </div>`;
        } else {
            newAnswer = `<div class="input-group mt-2">
                <input type="radio" class="form-check-input" name="correct_answers[${questionId}]" value="${existingAnswerCount}" required>
                <input type="text" class="form-control consistent-width-input" id="answer_text-${questionId}-${existingAnswerCount}" name="answers[${questionId}][]" placeholder="Enter answer option" required>
                <button type="button" class="btn btn-danger" onclick="removeAnswerOption(this, ${questionId})" style="border-radius: 10px; height: 38px; margin-left:10px;"><i class="fas fa-trash"></i></button>
            </div>`;
        }

        const answerGroup = document.createElement('div');
        answerGroup.innerHTML = newAnswer;

        // Append the existing "Add Option" button to the new answer group
        const addOptionButton = answerContainer.querySelector('.btn-success.add-option-btn');
        if (addOptionButton) {
            addOptionButton.remove();
            const inputGroup = answerGroup.querySelector('.input-group');
            inputGroup.appendChild(addOptionButton);
        } else {
            const newAddOptionButton = document.createElement('button');
            newAddOptionButton.type = 'button';
            newAddOptionButton.className = 'btn btn-success add-option-btn';
            newAddOptionButton.textContent = 'Add Option';
            newAddOptionButton.setAttribute('onclick', `addAnswerOption(${questionId})`);
            const inputGroup = answerGroup.querySelector('.input-group');
            inputGroup.appendChild(newAddOptionButton);
        }

        answerContainer.appendChild(answerGroup);
    }

    function removeAnswerOption(buttonElement, questionId) {
        const answerContainer = document.getElementById(`answer-container-${questionId}`);
        const remainingOptions = answerContainer.querySelectorAll('.input-group').length;

        if (remainingOptions <= 2) {
            showErrorModal('Please add at least two answer options for multiple choice or checkbox questions.');
        } else {
            const answerGroup = buttonElement.closest('.input-group');
            const addOptionButton = answerGroup.querySelector('.btn-success.add-option-btn');
            answerGroup.remove();

            const updatedRemainingOptions = answerContainer.querySelectorAll('.input-group').length;

            // Ensure the "Add Option" button is appended to the last input group
            moveAddOptionButtonToLastRow(answerContainer, addOptionButton);
        }
    }

    function moveAddOptionButtonToLastRow(answerContainer, addOptionButton) {
        if (addOptionButton) {
            addOptionButton.remove();
            const lastAnswerGroup = answerContainer.querySelector('.input-group:last-child');
            if (lastAnswerGroup) {
                lastAnswerGroup.querySelector('button.btn-danger').insertAdjacentElement('afterend', addOptionButton);
            } else {
                answerContainer.appendChild(addOptionButton);
            }
        }
    }

    function saveFormState() {
        const questionBlocks = document.querySelectorAll('.question-block');
        const formState = [];

        questionBlocks.forEach((block, index) => {
            const questionInput = block.querySelector('input[id^="question-"]');
            const questionTypeSelect = block.querySelector('select[id^="question_type-"]');
            const answerInputs = block.querySelectorAll('input[id^="answer_text-"]');

            const questionState = {
                question: questionInput.value,
                type: questionTypeSelect.value,
                answers: []
            };

            answerInputs.forEach(answerInput => {
                questionState.answers.push(answerInput.value);
            });

            formState.push(questionState);
        });

        localStorage.setItem('formState', JSON.stringify(formState));
    }

    function restoreFormState() {
        const formState = JSON.parse(localStorage.getItem('formState'));
        if (formState) {
            formState.forEach((questionState, index) => {
                const questionBlock = document.getElementById(`question-block-${index}`);
                if (questionBlock) {
                    const questionInput = questionBlock.querySelector('input[id^="question-"]');
                    const questionTypeSelect = questionBlock.querySelector('select[id^="question_type-"]');
                    const answerContainer = questionBlock.querySelector('div[id^="answer-container"]');

                    questionInput.value = questionState.question;
                    questionTypeSelect.value = questionState.type;
                    changeQuestionType(questionTypeSelect);

                    questionState.answers.forEach((answer, answerIndex) => {
                        if (answerIndex >= 2) { // If more than 2 answers, add additional options
                            addAnswerOption(index);
                        }
                        const answerInput = answerContainer.querySelectorAll(
                            'input[id^="answer_text-"]')[answerIndex];
                        if (answerInput) {
                            answerInput.value = answer;
                        }
                    });
                } else {
                    addQuestionField();
                    const questionBlock = document.getElementById(`question-block-${index}`);
                    const questionInput = questionBlock.querySelector('input[id^="question-"]');
                    const questionTypeSelect = questionBlock.querySelector('select[id^="question_type-"]');
                    const answerContainer = questionBlock.querySelector('div[id^="answer-container"]');

                    questionInput.value = questionState.question;
                    questionTypeSelect.value = questionState.type;
                    changeQuestionType(questionTypeSelect);

                    questionState.answers.forEach((answer, answerIndex) => {
                        if (answerIndex >= 2) { // If more than 2 answers, add additional options
                            addAnswerOption(index);
                        }
                        const answerInput = answerContainer.querySelectorAll(
                            'input[id^="answer_text-"]')[answerIndex];
                        if (answerInput) {
                            answerInput.value = answer;
                        }
                    });
                }
            });
        }
    }

    window.addEventListener('beforeunload', function () {
        localStorage.removeItem('formState');
    });

    document.addEventListener('DOMContentLoaded', function () {
        restoreFormState();
    });

</script>
@endsection
