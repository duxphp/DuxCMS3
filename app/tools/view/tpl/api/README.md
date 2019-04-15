<p align="center">
  <a href="https://github.com/duxphp/duxphp">
   <img alt="DuxPHP" src="/public/images/logo.png">
  </a>
</p>

<p align="center">
  为快速开发而生
</p>

# 说明

该文档由 DuxPHP 系统自动生成，请严格按照注释规则编写 Api 注释，请求可以使用 "Json" 或 "From" 类型，基础请求请在 "Header" 中进行传递

# 请求

每次请求必须携带以下参数

|字段|说明|类型|
|---|---|---|
| `label` |`接口名`|`String`|
| `token` |`接口密钥`|`String`|

# 示例

注释编写示例

```php
namespace app\article\api;

/**
 * 文章列表
 */

class IndexApi {

    /**
     * 文章列表
     * @method GET
     * @param inetger $class_id 分类id
     * @return inetger $code 200
     * @return string $message ok
     * @return json $result [{"title":"标题"}]
     * @field string $title 文章标题
     */
    public function index() {
        ...
    }

}
```

