# think-editor
用编辑器或者ide 打开 ThinkPHP 中的文件项目  
## 安装
`composer require yangweijie/think-editor`

~~~
// editor 配置中设置打开的 编辑器
'editor'   => ‘vscode’,
~~~

## 其他打开方式
### sublime
需要安装 GitHub - [thecotne/subl-protocol: sublime text protocol](https://github.com/thecotne/subl-protocol) 插件

### phpstorm
参考 [laravel-debugbar 中正确使用 ide phpstorm 打开项目文件的方式 | Laravel China 社区](https://learnku.com/articles/77072) 文章放置 js 和 添加注册表即可

## 打开协议的链接生成

~~~
use yangweijie\editor\Editor;

Editor::getEditorHref($file, $line);
~~~
