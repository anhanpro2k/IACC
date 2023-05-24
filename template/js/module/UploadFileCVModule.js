export default function UploadFileCVModule() {
    (function ($) {
        $.fn.uploader = function (options) {
          var settings = $.extend(
            {
              MessageAreaText: 'No files selected.',
              MessageAreaTextWithFiles: 'File List:',
              DefaultErrorMessage: 'Unable to open this file.',
              BadTypeErrorMessage: 'We cannot accept this file type at this time.',
              acceptedFileTypes: [
                'pdf',
                'bmp',
                'tif',
                'tiff',
                'xps',
                'doc',
                'docx',
                'fax',
                'wmp',
                'ico',
                'txt',
                'cs',
                'rtf',
                'xls',  
                'xlsx',
              ],
            },
            options
          );
    
          var uploadId = 1;
          //update the messaging
          $('.file-uploader__message-area p').text(options.MessageAreaText || settings.MessageAreaText);
    
          //create and add the file list and the hidden input list
          var fileList = $('<ul class="file-list"></ul>');
          var hiddenInputs = $('<div class="hidden-inputs hidden"></div>');
          $('.file-uploader__message-area').before(fileList);
          $('.file-list').before(hiddenInputs);
    
          //when choosing a file, add the name to the list and copy the file input into the hidden inputs
          $('.file-chooser__input').on('change', function () {
            var files = document.querySelector('.file-chooser__input').files;
    
            for (var i = 0; i < files.length; i++) {
    
              var file = files[i];
              var fileName = file.name.match(/([^\\\/]+)$/)[0];
    
              //clear any error condition
              $('.file-chooser').removeClass('error');
              $('.error-message').remove();
    
              //validate the file
              var check = checkFile(fileName);
              if (check === 'valid') {
                // move the 'real' one to hidden list
                $('.hidden-inputs').append($('#file-chooser__input'));
    
                //insert a clone after the hiddens (copy the event handlers too)
                $('.file-chooser').append($('#file-chooser__input').clone({ withDataAndEvents: true }));
    
                //add the name and a remove button to the file-list
                $('.f-c-file-list').append(
                  '<div class="f-c-file-item" style="display: none;"><span class="file-list__name"><span class="dot"></span>' +
                    fileName +
                    '</div>'
                );
                $('.f-c-file-list').find('.f-c-file-item:last').show(800);

              } else {
                //indicate that the file is not ok
                $('.file-chooser').addClass('error');
                var errorText = options.DefaultErrorMessage || settings.DefaultErrorMessage;
    
                if (check === 'badFileName') {
                  errorText = options.BadTypeErrorMessage || settings.BadTypeErrorMessage;
                }
    
                $('#file-chooser__input').after('<p class="error-message">' + errorText + '</p>');
              }
            }
          });
    
          var checkFile = function (fileName) {
            var accepted = 'invalid',
              acceptedFileTypes = settings.acceptedFileTypes,
              regex;
    
            for (var i = 0; i < acceptedFileTypes.length; i++) {
              regex = new RegExp('\\.' + acceptedFileTypes[i] + '$', 'i');
    
              if (regex.test(fileName)) {
                accepted = 'valid';
                break;
              } else {
                accepted = 'badFileName';
              }
            }
    
            return accepted;
          };
        };
      })($);
    
      //init
      $(document).ready(function () {
        $('.fileUploader').uploader({
          MessageAreaText: 'No files selected. Please select a file.',
        });
      });
}