<!-- resources/views/components/head/tinymce-config.blade.php -->

<!-- <script src="https://cdn.tiny.cloud/1/m23rrc3l2stmyl5ch8b6jqehgykpnxlazo4uew6p9d3rxb0t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
</script> -->


<!-- resources/views/components/head/tinymce-config.blade.php -->

<script src="https://cdn.tiny.cloud/1/m23rrc3l2stmyl5ch8b6jqehgykpnxlazo4uew6p9d3rxb0t/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>

    const images_upload_handler_callback = (blobInfo, progress) => new Promise((resolve,reject) =>{

        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '{{ route('admin.upload_image') }}');

        xhr.upload.onprogress =(e) => {
            progress(e.loaded/e.total * 100);
        };

        xhr.onload = () => {
            if(xhr.status === 403){
                reject({message: 'HTTP Error: ' + xhr.status, remove: true});
                return;
            }

            if(xhr.status < 200 || xhr.status >= 300){
                reject('HTTP Error: ' + xhr.status);
                return;
            }

            const json = JSON.parse(xhr.responseText);

            if(!json || typeof json.location !== 'string'){
                reject('Invalid JSON: ' + xhr.responseText);
                return;
            }

            resolve(json.location);
        };

        xhr.onerror = () => {
            reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('file', blobInfo.blob(), blobInfo.filename());
        xhr.send(formData);

    });

    tinymce.init({
        selector: '.tinymce', // Replace 'content' with the ID of your textarea,
        plugins: 'image',
        menubar: 'insert',
        toolbar: 'bold italic underline strikethrough | link image media table mergetags | align | tinycomments | checklist numlist bullist | emoticons charmap',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
        image_upload_url: '{{ route('admin.upload_image') }}',
        images_upload_handler: images_upload_handler_callback
    });    

    tinymce.init({
        selector: '.tinymce', // Replace 'content' with the ID of your textarea,
        plugins: 'image',
        menubar: 'insert',
        toolbar: 'bold italic underline strikethrough | link image media table mergetags | align | tinycomments | checklist numlist bullist | emoticons charmap',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
        image_upload_url: '{{ route('admin.upload_image') }}',
        images_upload_handler: images_upload_handler_callback
    });
</script>