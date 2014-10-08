CKEDITOR.editorConfig = function(config) {
  config.forcePasteAsPlainText = false;
  config.basicEntities = false;
  config.entities = false;
  config.entities_latin = false;
  config.entities_greek = false;
  config.entities_processNumerical = false;
  config.fillEmptyBlocks = function (element) {
    return true;
  };

  config.allowedContent = true;
  config.removeFormatAttributes = [];
  config.removeFormatTags = [];
};