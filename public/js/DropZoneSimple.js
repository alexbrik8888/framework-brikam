class DropZoneSimple {
    constructor(element) {
        this.id = element;
        this.obj = null;
        this.input = null;
        this.body = null;
        this.preview = null;
        this.filehtml = null;
        this.files = [];
        this.filesBase64 = [];
        this.cloneBody = null;
    }
    clearDropZone() {
        this.files = [];
        this.input.value  = '';
        this.returnFirstBody();
    }
    getFile() { return this.files;}
    getFileBase64(){return this.filesBase64;}
    getFileType(file){
        let ext =  file.name.split('.');
        ext = ext[ext.length-1];
        let image =  ['jpg','jpeg','jpe','jif','jfif','jfi','png','apng','gif','webp','avif','heif', 'heic','svg'];
        return (image.indexOf(ext.toLowerCase())!= -1)? true:false;
    }
    getFileByIndex(index){
        return this.filesBase64[index];
    }
    getfileByPrevieObj(obj){
        let preview  = obj.closest('[preview]');

        return this.filesBase64[preview.dataset.index];
    }

    addPreview(src64base,file,index){
        var clonePrev  =  this.preview.cloneNode(true);
        let ext =  file.name.split('.');
        ext = ext[ext.length-1];
        clonePrev.dataset.ext = ext;
        clonePrev.dataset.index = index;
        Object.assign(clonePrev.style,{display:'inline-block'});
        if(this.getFileType(file)) {
            let img = clonePrev.querySelector('[show]');
            if(img != null &&  img.tagName.toLowerCase() == 'img' )
                    img.src = src64base;
            else {
                    let image = document.createElement("img");
                    Object.assign(image.classList,img.classList);
                    Object.assign(image.style,image.style);
                    image.src = src64base;
                    image.setAttribute('show','');
                    img.after(image);
                    img.remove();
            }
            let name =   clonePrev.querySelector('[fileName]');
            if(name)
                name.innerHTML = file.name;

            this.body.appendChild(clonePrev);
        } else {
            let target = clonePrev.querySelector('[show]');
            if(target != null && target.tagName.toLowerCase() == 'img') {
                let div = document.createElement("div");
                Object.assign(div.classList,target.classList);
                Object.assign(div.style,target.style);
                div.classList.add('ico-doc');
                div.innerHTML = ext;
                div.setAttribute('show','');
                target.after(div);
                target.remove();
            }
            let name = clonePrev.querySelector('[fileName]');
            if(name)
                name.innerHTML = file.name;
            this.body.appendChild(clonePrev);
        }
    }
    initDropZone() {
        this.obj = document.querySelector(this.id);
        if(!this.obj.hasAttribute('dropzone'))
            this.obj = this.obj.querySelector('[dropzone]');
        if(this.obj) {
            this.obj.addEventListener("dragstart", (event) => {
                event.target.classList.add('hover');
            });
            this.obj.addEventListener("dragleave", (event) => {
                event.target.classList.remove('hover');
            });
            return true;
        } else {
            throw  new Error('Error attr dropzone  not found  add attr to tag');
            return false;
        }
    }
    initInput() {
        var $this = this;
        this.input =  this.obj.querySelector('input[filetarget]');
        if(!this.input){
            throw  new Error('Error attr filetarget  not found  add attr to tag');
            return false;
        }
        if(this.body &&  this.preview) {
            this.input.addEventListener('change', (event) => {
               if(event.target.files.length != 0) {
                   this.files = [];
                   this.filesBase64 = [];
                   this.obj.classList.remove('hover');
                   this.obj.classList.add('dropped');
                   this.body.innerHTML = '';
                   for (var i = 0; i < event.target.files.length; i++) {
                       var reader = new FileReaderEx();
                       this.files.push(event.target.files[i]);
                       reader.file = event.target.files[i];
                       reader.index = i;
                       reader.readAsDataURL(event.target.files[i]);
                       reader.onload = function (e) {
                           $this.filesBase64.push({'filebody':e.target.result,'uuid':null,'name':e.target.file.name,'type':e.target.file.type});
                           $this.addPreview(e.target.result, e.target.file, e.target.index);
                       };
                   }
               }
            });
            return  true;
        } else {
            throw  new Error('Error divbody and  divpreview  and image  not found  add attr to tag');
            return false;
        }
    }
    initBody() {
        this.body =  this.obj.querySelector('[bodypreview]');
        if(!this.body){
            throw  new Error('Error attr filetarget  not found  add attr to tag');
            return false;
        }
        this.cloneBody =this.body.cloneNode(true)
        this.cloneBody.querySelector('[preview]').style.display = 'none';
        return true;
    }
    initPreview() {
        this.preview = this.obj.querySelector('[preview]');
        if(!this.preview){
            throw  new Error('Error attr preview  not found  add attr to tag');
            return false;
        }
        Object.assign(this.preview.style,{display:'none'});
        return true;
    }
    addFileInput(fileInput) {
        var $this = this;
        for(var i = 0; i < fileInput.length;i++) {
             var temp  = [];
             for(var j = 0; j <  this.files.length;j++)
                 temp.push(this.files[j]);
            temp.push(fileInput[i]);
            this.files = temp;
            var reader = new FileReaderEx();
            reader.file = fileInput[i];
            reader.index = this.files.length-1;
            reader.readAsDataURL(fileInput[i]);
            reader.onload =  function(e){
                $this.filesBase64.push({'filebody':e.target.result,'uuid':null,'name':e.target.file.name,'type':e.target.file.type});
                $this.addPreview(e.target.result,e.target.file,e.target.index);
            };
        }
    }
    addFileUrl(fileUrl,name,uuid = null) {
        var  $this = this;
        var request = new XMLHttpRequest();
            request.open('GET',fileUrl, true);
            request.responseType = 'blob';
            request.onload = function() {
                let  reader = new  FileReaderEx();
                let file = new File([request.response],name);
                var temp  = [];
                for(var j = 0; j <  $this.files.length;j++)
                    temp.push($this.files[j]);
                temp.push(file);
                $this.files = temp;
                reader.file = $this.files[$this.files.length-1];
                reader.index = $this.files.length-1;
                reader.readAsDataURL(request.response);
                reader.onload =  function(e){
                    $this.filesBase64.push({'filebody':e.target.result,'uuid':uuid,'name':e.target.file.name,'type':e.target.file.type});
                    $this.addPreview(e.target.result,e.target.file,e.target.index);
                };
            };
            request.send();
    }
    removeFileByName(name) {
        for (var i =0; i < this.files.length;i++) {
            if(this.files[i].name.indexOf(name) != -1){
                let target =  this.body.querySelector("[data-index='"+i+"']");
                target.remove();
                this.files.splice(i,1);
            }
        }
        this.drawIndex();
        this.returnFirstBody();
    }
    removeFileByIndex(index) {
        this.files.splice(index,1);
        //this.filesBase64.splice(preview.dataset.index,1);
        let target =  this.body.querySelector("[data-index='"+index+"']");
        target.remove();
        this.drawIndex()
        this.returnFirstBody();
    }
    returnFirstBody(){
        if(this.files.length == 0) {
            this.body.innerHTML = this.cloneBody.innerHTML;
            console.log('empty');
            this.input.value = '';
        }
    }
    drawIndex(){
       let all =  document.querySelectorAll('[preview]');
       for(let i = 0; i < this.files.length;i++)  all[i].dataset.index = i;
    }
    removeFile(obj) {
        let preview  = obj.closest('[preview]');
        this.files.splice(preview.dataset.index,1);
        this.filesBase64.splice(preview.dataset.index,1);
        preview.remove();
        this.drawIndex()
        this.returnFirstBody()
    }
    init() {
        if(this.initDropZone())
            if(this.initBody())
                if(this. initPreview()) {
                    if (this.initInput())
                               return false;
                }
    }
    clear(){
        this.files = [];
        this.filesBase64 = [];
        console.log(this.body);
        this.body.innerHTML = '';
        try {
            this.input.value = null;
        } catch(ex) { }
        if (this.input.value) {
            this.input.parentNode.replaceChild(this.input.cloneNode(true), this.input);
        }
    }
}
