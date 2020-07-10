Dropzone.options.myDropzone = {
    paramName: "file",
    url:  'upload',
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: true,
    parallelUploads: 5,
    maxFilesize: 30, //MB
    acceptedFiles: ".png",
    dictDefaultMessage: "To add file click/add file here",

    init:function(){
        let myDropzone = this;

        let btn = document.getElementById("addImage");
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            myDropzone.processQueue();
        });

        myDropzone.on("sending", function (file, xhr, formData) {
            let frm = $('#my-Dropzone').serializeArray();
            $.each(frm, function (key, el) {
                formData.append(el.name, el.value)
            })
        });

        myDropzone.on("error", function (file) {
            alert('There is some thing wrong, Please try again!');
        });

        myDropzone.on("success", function (file, response) {
            if (response.uploaded)
                alert('File Uploaded: ' + file.name);
        });

        myDropzone.on("addedfile", function (file)  {
            file.previewElement.addEventListener("click", function(){
                myDropzone.removedfile(file);
            });
        });

        myDropzone.on("maxfilesexceeded", function (file) {
            myDropzone.removedfile;
        });
    }
};