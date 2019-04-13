
<p align="center">
  <a href="https://github.com/duxphp/DuxCMS3">
   <img alt="DuxCMS" src="https://github.com/duxphp/DuxPHP-Document/blob/master/logo.png?raw=true">
  </a>
</p>

<p align="center">
  为快速站点开发而生
</p>

<p align="center">
  <a href="https://github.com/duxphp/DuxCMS3">
    <img alt="maven" src="https://img.shields.io/badge/duxcms-v3-blue.svg">
  </a>

  <a href="http://zlib.net/zlib_license.html">
    <img alt="code style" src="https://img.shields.io/badge/zlib-licenses-brightgreen.svg">
  </a>
</p>

# 简介

DuxCMS 是一款基于PHP/MYSQL开发的、专注于多种平台的基础化内容管理框架

# 特点

- **模块化**：采用类HMVC模式，将系统内部交互采用hook方式，除基础应用外模块之间互相独立互不影响

- **轻量级**：底层采用轻量级框架开发，保留必要类库，提升系统速度与降低学习成本

- **多终端支持**：系统采用pc+mobile+api多终端访问模式，便于多种用户需求，兼容传统模板开发与多种类app程序

- **高度灵活**：系统采用composer作为依赖，同时封装常用类库

- **操作简单**：简化用户后台操作体验，完全符合快速上手使用

- **便捷标签**：模板采用注释类标签语法，三种格式标签，简单易开发

- **代码开源**：系统开放源代码，方便开发者二次开发与后期协作维护


# 适用范围

- 个人网站

- 企业/新闻类站点

- 图片展示站

- 电子商务开发

- 管理系统开发

# 推荐环境

- 语言版本：PHP 7.1+

- 数据库版本：Mysql 5.6+

- WEB服务器：Apache/Nginx

- 后台浏览器：Chrome 30+ IE11+ FireFox 22+


# 讨论

QQ群：131331864

> 本系统非盈利产品，为防止垃圾广告和水群已开启收费入群，收费入群并不代表我们可以无条件回答您的问题，入群之前请仔细查看文档，常见安装等问题通过搜索引擎解决，切勿做伸手党

# bug反馈

[issues反馈](https://github.com/duxphp/DuxCMS3/issues)
    
# 版权说明

本项目使用zlib开源协议，您可以在协议允许范围内进行进行商业或非商业项目使用

# 开发团队

湖南聚匠信息科技有限公司


# 安装说明

1. 安装composer，请查看文档进行安装与更换国内镜像

    

   ```
   https://www.phpcomposer.com/
   ```
   

2. 建立站点指向Dux程序根目录，目录内命令安装依赖

   ```
   composer install
   ```

3. 设置站点伪静态规则

   nginx规则

   ```
   if (!-e $request_filename) {
      rewrite  ^(.*)$  /index.php?$1  last;
      break;
   }
   ```

   apache规则

   ```
   RewriteEngine on
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteRule ^(.*)$ index.php?$1 [QSA,PT,L]
   ```

   iis规则

   ```
   <?xml version="1.0" encoding="UTF-8"?>
   <configuration>
   <system.webServer> 
   <rewrite>
   <rules>
   <rule name="rule 3S" stopProcessing="true">
   <match url="^(.*)$" />
   <conditi>
   <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
   <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
   </conditi>
   <action type="Rewrite" url="/index.php?{R:1}" appendQueryString="true" />
   </rule>
   </rules>
   </rewrite>
   </system.webServer>
   </configuration>
   ```

   

4. 设置mysql模式为非严格模式 (临时解决方案)

```
sql-mode=NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION
```



5. 访问站点绑定域名进入安装向导进行配置安装 