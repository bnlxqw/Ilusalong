(function () {
  var maximumBytes = 512 * 1024;
  var startingQuality = 0.82;
  var maximumDimension = 1600;
  var isRussian = document.documentElement.lang === 'ru';

  function message(english, russian) {
    return isRussian ? russian : english;
  }

  function compressImage(file) {
    return new Promise(function (resolve, reject) {
      var source = URL.createObjectURL(file);
      var image = new Image();

      image.onload = function () {
        URL.revokeObjectURL(source);
        var scale = Math.min(1, maximumDimension / Math.max(image.naturalWidth, image.naturalHeight));
        var width = Math.max(1, Math.round(image.naturalWidth * scale));
        var height = Math.max(1, Math.round(image.naturalHeight * scale));
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');

        if (!context) {
          reject(new Error(message('Your browser could not prepare this image.', 'Не удалось подготовить изображение в этом браузере.')));
          return;
        }

        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, width, height);
        context.drawImage(image, 0, 0, width, height);

        function encode(quality) {
          canvas.toBlob(function (blob) {
            if (!blob) {
              reject(new Error(message('Your browser could not compress this image.', 'Не удалось сжать изображение в этом браузере.')));
              return;
            }
            if (blob.size <= maximumBytes) {
              resolve(new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', {
                type: 'image/jpeg',
                lastModified: Date.now()
              }));
              return;
            }
            if (quality > 0.42) {
              encode(quality - 0.1);
              return;
            }
            if (width > 320 || height > 320) {
              width = Math.max(1, Math.round(width * 0.8));
              height = Math.max(1, Math.round(height * 0.8));
              canvas.width = width;
              canvas.height = height;
              context.fillStyle = '#ffffff';
              context.fillRect(0, 0, width, height);
              context.drawImage(image, 0, 0, width, height);
              encode(startingQuality);
              return;
            }
            reject(new Error(message('This image is too detailed to compress below 512 KB.', 'Изображение слишком детализировано, чтобы сжать его до 512 КБ.')));
          }, 'image/jpeg', quality);
        }

        canvas.width = width;
        canvas.height = height;
        context.fillStyle = '#ffffff';
        context.fillRect(0, 0, width, height);
        context.drawImage(image, 0, 0, width, height);
        encode(startingQuality);
      };

      image.onerror = function () {
        URL.revokeObjectURL(source);
        reject(new Error(message('This image could not be opened.', 'Не удалось открыть это изображение.')));
      };
      image.src = source;
    });
  }

  document.addEventListener('change', function (event) {
    var input = event.target;
    if (!input.matches('input[type="file"][name="image"]') || !input.files.length) {
      return;
    }

    var file = input.files[0];
    var status = input.parentNode.querySelector('.image-upload-status');
    if (!file.type || file.type.indexOf('image/') !== 0) {
      if (status) status.textContent = message('Choose an image file.', 'Выберите файл изображения.');
      input.value = '';
      return;
    }

    if (status) status.textContent = message('Optimizing image…', 'Подготовка изображения…');
    input.disabled = true;
    compressImage(file).then(function (compressedFile) {
      var transfer = new DataTransfer();
      transfer.items.add(compressedFile);
      input.files = transfer.files;
      if (status) {
        status.textContent = message('Ready: ', 'Готово: ') + Math.round(compressedFile.size / 1024) + ' KB.';
      }
    }).catch(function (error) {
      input.value = '';
      if (status) status.textContent = error.message;
    }).then(function () {
      input.disabled = false;
    });
  });
}());
