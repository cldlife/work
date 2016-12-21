<?php 
/**
 * @desc kissy editor
 * @version 1.4.8
 * @example 
 * 
 * kissy::init(
 * 	array(
 *  'content' => '',
 * 	'uploadUrl' => $this->getDeUrl('/util/upload'), 
 * 	'uploadFileToken' => Yii::app()->getRequest()->getCsrfToken(), 
 * 	'city' => 383,
 *  'dataId' => 111111)
 * );
 */
class kissy {
  
  public static $version = '1.4.8';
  
  public static $city = '';
  
  public static $dataId = '';
  
  public static $id = 'editorContent';
  
  public static $width = '830px';
  
  public static $height = '600px';
  
  public static $content = '';
  
  public static $draftSaveKey = 'bkman';
  
  public static $sourceView = TRUE;
  
  public static $uploadUrl = 'upload.php';
  
  public static $uploadFileSuffix = 'png,jpg,jpeg,gif';
  
  public static $uploadFileInput = 'Filedata';
  
  public static $uploadFileSizeLimit = 3000;
  
  public static $uploadFileToken = '';
  
  public static $simple = FALSE;
  
  public static $addPlugins = array();
  
  //内置全部插件(部分kissy插件这里没有添加)
  private static $fullPlugins = array('bold', 'italic', 'underline', 'strike-through', 'font-family', 'font-size', 'fore-color', 'back-color', 'resize', 'draft', 'separator', 'remove-format', 'undo', 'separator', 'preview', 'maximize', 'justify-left', 'justify-center', 'justify-right', 'separator', 'indent', 'outdent', 'unordered-list', 'ordered-list', 'page-break', 'separator', 'image', 'link', 'drag-upload');
  
  //simple
  private static $simplePlugins = array('image', 'separator', 'preview', 'maximize', 'resize', 'draft', 'drag-upload');
  
  //init
  public static function init ($config = array()) {
    $e = new self();
    if ($config) {
      foreach ($config as $k => $v) {
        if (!isset(self::$$k)) continue;
        $v = str_replace(array("\t", "\n", "\r", "\r\n"), "", $v);
        if ($k == 'content' || $k == 'uploadFileToken') {
          $v = addslashes($v);
        }
        $e->set($k, $v);
      }
    }

    return $e->createEditor();
  }
  
  //create editor
  private function createEditor () {
    $plugins = array();
    $sourcePluginCss = '';
    
    //是否开启源代码插件
    if ($this->get('sourceView') == TRUE) {
      $plugins[] = 'source-area';
      $plugins[] = 'separator';
      $plugins[] = 'checkbox-source-area';
      $sourcePluginCss = 'S.all(".ks-editor-draft").css({\'width\': \'auto\'});setTimeout(function (){ S.all(".ks-editor-draft-title").remove(); }, 3000);';
    }
    
    if ($this->get('simple') == TRUE) {
      //自定义插件
      $addPlugins = $this->get('addPlugins');
      if (is_array($addPlugins) && $addPlugins) {
        $plugins = array_merge($plugins, $addPlugins);
      } else {
        $simplePlugins = $this->get('simplePlugins');
        $plugins = array_merge($plugins, $simplePlugins);
      }
    } else {
      $plugins = array_merge($plugins, $this->get('fullPlugins'));
    }
    
    //启用插件
    $excutePlugins = implode(',', $plugins);
    
    return <<<EOT
    {$this->__createBaseStyle()}
    {$this->__createBaseJs()}
    <script type="text/javascript" src="http://f.shiyi11.com/ui/js/common/editor/drag-upload.js"></script>
    <div id="editorContainer_{$this->get('id')}" style="margin:1px"><span id="editor-loading">加载中，请稍后...</span></div>
    <textarea id="{$this->get('id')}" name="{$this->get('id')}" style="display:none"></textarea>
    <script type="text/javascript">
    (function () {
      KISSY.config("combine", true);
      KISSY.use("editor", function (S, Editor) {
        var cfg = {
          focused: true,
          attachForm: true,
          data: "{$this->get('content')}",
          fromTextarea: "#{$this->get('id')}",
          render: "#editorContainer_{$this->get('id')}",
          width: '{$this->get('width')}',
          height: "{$this->get('height')}",
          //自定义样式
          customStyle:"p{line-height: 28px;text-indent: 2em;}",
          //自定义外部样式
          //customLink:["http://test.com/style.css"]
        };

        var plugins = ("{$excutePlugins}").split(",");

        var fullPlugins = [];

        S.each(plugins, function (p, i) {
          fullPlugins[i] = "editor/plugin/" + p;
        });

        var pluginConfig = {
          "image": {
            defaultMargin: 0,
            // remote:false,
            upload: {
              serverUrl: "{$this->get('uploadUrl')}",
              suffix: "{$this->get('uploadFileSuffix')}",
              fileInput: "{$this->get('uploadFileInput')}",
              sizeLimit: {$this->get('uploadFileSizeLimit')},
              extraHTML: "<input type='hidden' id='city' name='city' value='{$this->get("city")}'><input type='hidden' id='_sh_token_' name='_sh_token_' value='{$this->get("uploadFileToken")}'> <input type='hidden' id='data_id' name='data_id' value='{$this->get("dataId")}'>"
            },
            "multiple-upload": {
              suffix: "{$this->get('uploadFileSuffix')}",
              fileInput: "{$this->get('uploadFileInput')}",
              serverUrl: "{$this->get('uploadUrl')}",
              numberLimit: 20
            }
          },
          "link": {
            target: "_blank"
          },
          "draft": {
            // 当前编辑器的历史是否要单独保存到一个键值而不是公用
            saveKey:"{$this->get('draftSaveKey')}",
            interval: 5,
            limit: 10,
            "helpHtml": "<div style='width:200px;'>" +
              "<div style='padding:5px;'>草稿箱能够自动保存您最新编辑的内容，" +
              "如果发现内容丢失，" +
              "请选择恢复编辑历史</div></div>"
          },
          "resize": {
            //direction:["y"]
          },
          "drag-upload": {
            suffix: "{$this->get('uploadFileSuffix')}",
            fileInput: "{$this->get('uploadFileInput')}",
            serverUrl: "{$this->get('uploadUrl')}",
            serverParams: {
                _sh_token_: function () {
                    return '{$this->get("uploadFileToken")}';
                }
            },
          }
        };
        
        //fullPlugins.push("editor/plugin/drag-upload");
        KISSY.use(fullPlugins, function (S) {
          var args = S.makeArray(arguments);
          args.shift();
          S.each(args, function (arg, i) {
            var argStr = plugins[i], cfg;
            if (cfg = pluginConfig[argStr]) {
              args[i] = new arg(cfg);
            }
          });

      	  cfg.plugins = args;

          var editor = Editor.decorate(cfg.fromTextarea, cfg);
          {$sourcePluginCss}
          
          S.all("#editor-loading").remove();
              
          $("form").live('submit', function(e){
            $('.ks-editor-textarea').val(editor.getFormatData());
          });
          
          //setInterval(function (){ editor.sync();}, 500);
              
          window.EditorObject = editor;
        });
      });
    })();
    </script> 
EOT;
  }
  
  private function __createBaseStyle () {
    return <<<EOT
    <link href="http://g.tbcdn.cn/kissy/k/{$this->get("version")}/css/dpl/base-min.css" rel="stylesheet"/>
    <link href="http://g.tbcdn.cn/kissy/k/{$this->get("version")}/editor/theme/cool/editor-min.css?1" rel="stylesheet"/>
EOT;
  }
  
  private function __createBaseJs () {
    return <<<EOT
<script src="http://g.tbcdn.cn/kissy/k/{$this->get("version")}/seed.js" data-config="{combine:true}"></script>
EOT;
  }
  
  //get property
  public function get ($key) {
    return self::$$key;
  }
  
  //get property
  public function set ($key, $value) {
    return self::$$key = $value;
  }
}
?>
