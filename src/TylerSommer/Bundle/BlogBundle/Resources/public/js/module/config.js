CKEDITOR.editorConfig = function(config) {
  config.forcePasteAsPlainText = false;
  config.basicEntities = true;
  config.entities = false;
  config.entities_latin = true;
  config.entities_greek = true;
  config.entities_processNumerical = false;
  config.fillEmptyBlocks = function (element) {
    return true;
  };

  config.allowedContent = true;
  config.removeFormatAttributes = [];
  config.removeFormatTags = [];
};