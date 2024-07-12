# Setup project

- Clone project

```cmd
git clone ssh://git@git.amaisoft.com:2224/amaisoft-innovation/laravel-base.git
```

- Install composer

```cmd
composer install
```

- Create database with project. Default `laravel_base`.

- Create `.env` file

```cmd
cp .env.example .env
```

- Generate `APP_KEY`

```cmd
php artisan key:gen
```

- Config `.env` file

```php
APP_NAME="Laravel Base"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL="http://laravel-base.abc"
APP_ADMIN_URL="http://localhost"
APP_USER_URL="http://localhost"
APP_LANG=en
APP_FAKER_LOCALE=en_US
APP_TIMEZONE=UTC

# 'stack' if have set telegram message
LOG_CHANNEL=daily

# Config with databse of project
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_base
DB_USERNAME=root
DB_PASSWORD=

# Config Telegram Message
TELEGRAM_LOG_LEVEL=debug
TELEGRAM_BOT_TOKEN=
TELEGRAM_LOG_GROUP_ID=
```

# Install pre-commit git

```bash
php artisan pre-commit:install
```

- Thêm tất cả các file đã thay đổi vào git stage và chạy thử:

```bash
php artisan pre-commit:check
```

# Quy tắc chung
## Các function phải có docblock và type của param truyền vào
Ví dụ:

```php
/**
 * Store User
 *
 * @param array $data
 * @return User
 */
public function store($data)
{
    return User::create($data);
}
```

```php
/**
 * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
 */
public function makeNewQuery()
{
    return User::isActive();
}
```

```php
/**
 * Set the relationships that should be eager loaded.
 *
 * @param  string|array  $relations
 * @param  string|\Closure|null  $callback
 * @return $this
 */
public function with($relations, $callback = null)
{
    $this->currentQuery()->with(...func_get_args());

    return $this;
}
```

## Các thuộc tính cần phải có docblock
Ví dụ:

```php
/**
 * @var integer
 */
protected $perPage = 10;
```

```php
/**
 * @var \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
 */
protected $query;
```

# Luồng code, chi tiết các thành phần

route -> middleware -> request -> controller -> service -> controller -> resource -> return


## Route

- Điều hướng request
- `route` phải được nhóm vào các nhóm gọn gàng
- Đặt tên `route` phải có ý nghĩa và không quá dài
- `route` lấy dữ liệu sẽ có method là `get`, thay đổi dữ liệu method sẽ là `post` (create, update, delete)
- CURD sử dụng 5 tên api cơ bản (`list`, `store`, `detail`, `update`, `destroy`).

## Middleware

- Xác nhận `auth`
- Chặn phân quyền

## Request

- Xử lý `validate`

## Controller

- Viết `middleware` vào `__construct()` của `controller`
- `Controller` sẽ xử lý lấy dữ liệu từ `Request` để gửi vào `Service`

## Service

- Xử lý logic.
- Query DB để lấy ra dữ liệu, cập nhật dữ liệu vào DB.
- Chú ý chỉ lấy ra những dữ liệu cần thiết.

## Resource

- Transform dữ liệu trước khi trả về.
- Riêng master data sẽ không có resource, phải transform dữ liệu trong service.

# Quy tắc viết Model

- Mỗi model sẽ tương ứng với 1 bảng.
- Mỗi model sẽ có một trait scope tương ứng trong thư mục `Scopes`. Model sẽ `use` trait scope đó.
- Tất cả scope sẽ được viết vào file scope không được viết vào model.
- Thư mục `Traits` dùng để chứa những code được sử dụng trong nhiều model. Ví dụ: nhiều model có status giống nhau có thể viết vào traits.

# Quy tắc viết Controller

- Một controller có thể thực hiện 1 chức năng hoặc nhiều chức năng liên quan đến nhau.
- Xử lý lấy params từ request trước khi truyền vào service ở controller.
- Không sử dụng `$request->all()`. Sử dụng `$request->only(['pr1', 'pr2'])`.
- Hạn chế truyền cả request vào service.
- Trong 1 controller chỉ gọi 1 service. Trong service sẽ gọi những service khác nếu cần thiết.

# Quy tắc viết Service

- Tất cả các service phải được extends abstract class `Service`.
- Khi thêm 1 `guard` mới vào hệ thống cần init thêm `ServiceRegister` vào `app/Providers/ServiceRegister`.
- Khi thêm một service thì phải register vào `app/Providers/ServiceRegister/*` tương ứng.

```php
$app->scoped(AuthService::class, function ($app) {
    return new AuthService();
});
```

- Khi sử dụng service thì phải gọi qua hàm static `getInstance()` để lấy đối tượng service để sử dụng.

# Cách sử dụng transaction

- Phải viết transaction khi tính năng thao tác create/update/delete tới nhiều bảng trong DB.
- Transaction phải tuân thủ cấu trúc bên dưới. Dưới `DB::commit()` chỉ được return dữ liệu. Không được gọi hàm khác.
- Nên viết transaction trong service (hàm được controller gọi).
- Không được gọi hàm có transaction trong một transaction khác. Điều này sẽ khiến transaction bị lồng nhau.
- Trong 1 transaction thứ tự update các bảng phải theo một luồng nhất định.

## Cấu trúc của một transaction

```php
try {
    DB::beginTransaction();

    // code here
    // make $data;

    DB::commit();
    return $data;
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}
```

hoặc

```php
$data = null;
try {
    DB::beginTransaction();

    // code here
    // modify $data;

    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}
return $data;
```

## Trong 1 transaction thứ tự update các bảng phải theo một luồng nhất định

- Thứ tự bảng khi update hoặc delete phải giống thứ tự bảng khi create
- Khi sử dụng transaction phải ghi Thứ tự bảng trong transaction đó vào file `transaction-follow.md`.
    - Full Class name: tên class chứa `DB::beginTransaction()`. Tên phải là full name. Ví dụ: `App\Services\User\UserService`.
    - Method name: tên method chứa `DB::beginTransaction()`.
    - Nội dung là thứ tự bảng được sử dụng

Ví dụ: Một sản phẩm có nhiều ảnh.

```php
// Create product: thứ tự bảng products -> images
$product = Product::create(['name' => 'Sản phẩm 1']);
$product->images()->create(['url' => 'https://img.example/img.jpg']);

// Delete product: thứ tự bảng products -> images
Product::where('id', 1)->delete();
Image::where('product_id', 1)->delete();
```

# Query log
```php
DB::enableQueryLog();
$queries = DB::getQueryLog();
```

# Quy tắc viết command

- Các command file phải được tổ chức trong các folder theo mục đích của từng module.

    - Ví dụ như `App\Console\Commands\System` chứa các command liên quan đến hệ thống, `App\Console\Commands\Fake` chứa các command liên quan đến fake data dữ liệu test.
- Tất cả command `$signature` đều phải định nghĩa `const` trong `app/Console/Kernel.php` bắt đầu với prefix `CMD_`.

- Phải định nghĩa `$description` trong các file command để biết mục đích của command. 

- Nội dung `$signature` theo cấu trúc `admin:<module>:<signature>` .

    - Ví dụ: `admin:sys:get-telegram-info` sẽ gồm prefix `admin:`, module `sys` là các command của hệ thống, signature là `get-telegram-info` mục đích là lấy thông tin kênh telegram. Hoặc `admin:fake:user` sẽ gồm prefix `admin:`, type `fake` là các command của module fake data, signature là `user` mục đích là fake user data.

- Đối với các command module `Fake` data chỉ được phép chạy khi `APP_ENV=local`.

# Routes

- Admin:

    |   No  | Name                  | Method    | URL                           |
    | ----- | -----                 | ---       | ---                           |
    |   1   | Change password       | POST      | admin/auth/change-password    |	
    |   2   | Login                 | POST      | admin/auth/login              |	
    |   3   | Logout                | POST      | admin/auth/logout             |	
    |   4   | Get current user      | GET       | admin/auth/me                 |	
    |   5   | Update current user   | POST      | admin/auth/me                 |	
    |   6   | Get master data       | GET       | admin/master-data             |	
    |   7   | Upload image          | POST      | admin/upload-image            |	
    |   8   | Get list users        | GET       | admin/users                   |	
    |   9   | Get user detail       | GET       | admin/users/{user}            |	
    |   10  | Update user           | POST      | admin/Susers/{user}            |	
    |   11  | Get zipcode           | GET       | admin/zipcode                 |
    |       |                       |           |                               |

- User:

    |   No  | Name                  | Method    | URL                           |
    | ----- | -----                 | ---       | ---                           |
    |   1   | Change password       | POST      | user/auth/change-password     |	
    |   2   | Login                 | POST      | user/auth/login               |	
    |   3   | Logout                | POST      | user/auth/logout              |	
    |   4   | Get current user      | GET       | user/auth/me                  |	
    |   5   | Update current user   | POST      | user/auth/me                  |	
    |   6   | Get master data       | GET       | user/master-data              |	
    |   7   | Upload image          | POST      | user/upload-image             |	
    |   8   | Register user         | POST      | user/auth/register            |	
    |   9   | Get zipcode           | GET       | user/zipcode                  |
    |       |                       |           |                               |
# Updating
...