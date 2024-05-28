<!-- resources/views/components/head/tinymce-config.blade.php -->

<script src="https://cdn.tiny.cloud/1/m23rrc3l2stmyl5ch8b6jqehgykpnxlazo4uew6p9d3rxb0t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: 'textarea#content', // Replace 'content' with the ID of your textarea,
        menubar: 'insert',
        toolbar: 'bold italic underline strikethrough | link image media table mergetags | align | tinycomments | checklist numlist bullist | emoticons charmap',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
    });
</script>
