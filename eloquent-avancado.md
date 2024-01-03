# Eloquent Avançado

### Seeders

#### Populando Tabelas

No Laravel, para popular tabelas no banco de dados usando Seeders, podemos utilizar o arquivo `DatabaseSeeder.php`. No método `run`, podemos adicionar registros usando comandos como:

```php
\DB::table('users')->insert([
    'name' => 'joao',
    'email' => 'joao@teste.com',
    'password' => bcrypt('1234')
])

\App\User::create([
    'name' => 'joaooutro',
    'email' => 'joaooutro@teste.com',
    'password' => bcrypt('1234')
])

```
#### Utilizando Seeders

Executamos o comando:
```php
php artisan db:seed
```
para inserir os dados no banco de dados. No entanto, existem maneiras mais eficientes, como a criação de Seeders específicos.

#### Criando um Novo Seeder
Para criar um novo Seeder pelo artisan, utilizamos o comando:

```php
php artisan make:seeder UsersTableSeeder

```

O Laravel cria o arquivo dentro da pasta `database/seeds`. Podemos usá-lo no método `run()` do DatabaseSeeder usando:

```php
$this->call(UsersTableSeeder::class);
```

#### Usando Factory para Dados Falsos
O Laravel fornece Factory para criar dados falsos de maneira mais profissional. A pasta `database/factories` contém arquivos como `UserFactory.php`. Para usar um Factory, dentro do UsersTableSeeder:

```php
factory(App\User::class)->make();
```

#### Executando Seeders Específicos
Para executar um Seeder específico usando o artisan, usamos o comando:

```php
php artisan db:seed --class=UsersTableSeeder
```

#### Utilizando o Faker
O arquivo `config/app.php` possui o comando `'faker_locale' => 'en_US'`, substituir por 'pt_BR'. 

Podemos criar múltiplos registros facilmente usando o Factory:
```php
factory(App\User::class, 20)->create();
```

#### Zerando todos os dados do banco com migrate
Ao criar todos os dados percebemos que se formos criar uma nova tabela 

```php
php artisan migrate:refresh 
```


#### Populando dados da tabela `categories`
Iremos criar uma nova factory para a tabela de categorias de POSTs, e logo em seguida um novo seed para implementar o Factory

```php
php artisan make:factory CategoryFactory --model=Category
php artisan make:seeder CategoriesTableSeeder
```

Com o `--model=Category` definimos o Model que ele irá consumir na Factory

O código dentro do `CategoryFactory.php` fica mais ou menos assim:

```php
use Faker\Generator as Faker;
$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name'=>$faker->text(15),
        'description'=>$faker->text(200)
    ];
});
```
No `CategoriesTableSeeder` usamos referência do factory que criamos:

```php
factory(App\Category::class, 20)->create();
```
Por fim, utilizamos o código para criar os seeds e preencher os dados da nossa tabela

```php
php artisan db:seed --class=CategoriesTableSeeder
```


#### Populando dados da tabela `posts`

Iremos criar uma nova factory para a tabela de POSTs, e logo em seguida um novo seed para implementar o Factory

```php
php artisan make:factory CommentFactory --model=Post
php artisan make:seeder CommentsTableSeeder
php artisan make:factory PostFactory --model=Post
php artisan make:seeder PostsTableSeeder
```

O `CommenFactory.php` ficardessa forma:

```php
$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'title'=>$faker->text(15),
        'content'=>$faker->text(200),
        'post_id'=>function(){
            return factory(App\post::class)->create()->id;
        }
    ];
});
```

O `PostFactory.php` ficará assim:

```php
use Faker\Generator as Faker;
$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'title'=>$faker->text(15),
        'content'=>$faker->text(200),
    ];
});
```
No `PostsTableSeeder` usamos referência do factory que criamos:

```php
factory(App\Comments::class)->create();
```


Caso ao executar algum desses comandos e constar que o arquivo não existe, provavelmente pode ser por causa do cache que é guardado no Laravel e ele pode não ter pegado as últimas alterações. Para solucionar isso utilizamos o comando:

```php
composer dump-autoload
```


Para a factory `PostFactory` iremos criar alguns métodos dentro dela, embora ela não tenha chave estrangeira, ela possui referências em outras tabelas.

Adicionaremos o método de detalhes do post no factory:

```php
use Faker\Generator as Faker;
$factory->define(App\Details::class, function (Faker $faker) {
    return [
        'status'=>$faker->randomElement(['publicado','rascunho']),
        'visibility'=>$faker->randomElement(['publico','privado']),
    ];
});

```

Criaremos o método `run` do `PostsTableSeeder`:

```php
factory(App\Post::class)->create()->each(function($post) {
    $post->comments()->savemany(
        factory(App\Comment::class,3)->make([
            'post_id'=>$post->id
        ])
    );
    
    $post->categories()->save(
        factory(App\Category::class)->make()
    );
    
    $post->details()->save(
        factory(App\Details::class)->make()
    );
});

// Estamos definindo que para cada Post novo ele irá adicionar 3 comentários, 1 detalhe e 1 categoria.
// Passando o array no método make do Comment para que os comentários não acabem gerando 
// um post cada. E então ele irá conseguir referenciar a apenas o post que está sendo criado.
```

Por fim, utilizamos o código para criar os seeds e preencher os dados da nossa tabela

```php
php artisan db:seed --class=PostsTableSeeder
```

No `DatabaseSeeder` no método `run` iremos fazer umas alterações para adicionar as novas classes que criamos.


Ele estava assim, até então:
```php
    $this->call(UsersTableSeeder::class);
```

Como adicionaremos mais de uma classe, usaremos array para envolver eles:
```php
    $this->call([
        CategoriesTableSeeder::class,
        CommentsTableSeeder::class,
        PostsTableSeeder::class,
        UsersTableSeeder::class
    ]);
```

Limparemos novamente o banco de dados para adicionar novos dados:

```php
php artisan migrate:refresh
```

Agora podemos usar o comando para popular todos os seeders de uma vez:
```php
php artisan db:seed
```


## Relações Polimórficas
