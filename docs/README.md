# unnamedComicAPI
暫未命名的漫畫API

## Prefix

>`api/`

## 首頁

URL | 頁面 | 其他
--- | --- | --- |
/ | 首頁 |
[auth](#Auth) | JWT 認證 |
[*](#JWTError) | JWT 認證錯誤 | token無效或過期
[comic](#Comic) | 漫畫列表 |
[publish](#Publish) | 發布 |
[service](#Service) | 服務 |

### <a name="Auth"></a> JWT 認證
URL | 頁面 | 其他
--- | --- | --- |
/auth | JWT | POST

>`/auth`

類型 | 參數名稱 | 必須
--- | --- | --- |
String | email | ✔
String | password | ✔

>success

```
{
    "token": *token*
}
```

>error

```
{
    "error": "invalid_credentials"
}
```

-----or-----

```
{
    "error": "could_not_create_token"
}
```

>使用GET方法

```
/url?token={token}
```

-----or-----

>使用Headers方式

```
Authorization: Bearer <token>
```

## <a name="JWTError"></a>Publish JWT 認證錯誤

URL | 頁面 | 其他
--- | --- | --- |
/* | * | *

>`/*?token={token}`

```
{
    "error": "token_invalid"
}
```

-----or-----

```
{
    "error": "token_expired"
}
```

### <a name="Comic"></a> 漫畫列表
URL | 頁面 | 其他
--- | --- | --- |
/comic/{id} | 單一漫畫資訊 | GET
/comic/{id}/cover | 單一漫畫封面 | GET
/comid/page/{page} | 所有漫畫列表 | GET, 依updated_at降冪排列, 10筆

>`/comic/{id}`

```
{
  "status": "success",
  "comic": {
    "id": *id*,
    "name": *name*,
    "summary": *summary*,
    "chapters": *chapters*,
    "created_at": *create_at*,
    "updated_at": *updated_at*
  },
  "chapters": [
    {
      "id": *id*,
      "comic_id": *comic_id*,
      "name": *name*,
      "imgs": *imgs*,
      "created_at": *created_at*,
      "updated_at": *updated_at*,
      "token": *token*,
    },
    ...
  ]
}
```

>`/comic/{id}/cover`
####漫畫封面 (Image)

>`/comic/page/{page}`
#### JSON Response

```
{
  "status": "success",
  "comics": [
    {
      "id": *id*,
      "name": *name*,
      "summary": *summary*,
      "created_at": *create_at*,
      "updated_at": *updated_at*
    },
    ...
  ]
}
```

### <a name="Publish"></a>Publish 發布
URL | 頁面 | 其他
--- | --- | --- |
/publish | 發布新漫畫 | POST, JWT
/publish/{id} | 發布新章節 | POST, JWT

>`/publish`

類型 | 參數名稱 | 必須
--- | --- | --- |
String | name | ✔
String | summary | ✔
File | cover | ✔

>success

```
{
    "status": "success",
    "info": {
        "name": *name*,
        "summary": *summary*,
        "updated_at": "updated_at",
        "created_at":"created_at",
        "id": *id*
    }
}
```

>error

```
{
    "status": "error",
    "msg": *msg[Array]*
}
```

-----or-----

```
{
    "status": "error",
    "msg": "Comic does not exist."
}
```

>`/publish/{id}`

類型 | 參數名稱 | 必須
--- | --- | --- |
String | name | ✔
File | image[] | ✔

>success

```
{
    "status": "success",
    "msg": "Upload successful."
}
```

>error

```
{
    "status": "error",
    "msg": *msg[Array]*
}
```

### <a name="Service"></a>Service 服務
URL | 頁面 | 其他
--- | --- | --- |
/service/register | 註冊帳號 | POST

>`/service/register`

類型 | 參數名稱 | 必須
--- | --- | --- |
String | email | ✔
String | password | ✔
String | password_confirmation | ✔
String | name | ✔

>success

```
{
    "status": "success",
    "msg": "Register successful."
}
```

>error

```
{
    "status": "error",
    "msg": *msg[Array]*
}
```