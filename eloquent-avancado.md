<center>

# Eloquent Avançado

</center>

<span style="font-size:30px;font-weight:bold">Seeders</span>

### Populando Tabelas

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
### Utilizando Seeders

Executamos o comando:
```php
php artisan db:seed
```
para inserir os dados no banco de dados. No entanto, existem maneiras mais eficientes, como a criação de Seeders específicos.

### Criando um Novo Seeder
Para criar um novo Seeder pelo artisan, utilizamos o comando:

```php
php artisan make:seeder UsersTableSeeder

```

O Laravel cria o arquivo dentro da pasta `database/seeds`. Podemos usá-lo no método `run()` do DatabaseSeeder usando:

```php
$this->call(UsersTableSeeder::class);
```

### Usando Factory para Dados Falsos
O Laravel fornece Factory para criar dados falsos de maneira mais profissional. A pasta `database/factories` contém arquivos como `UserFactory.php`. Para usar um Factory, dentro do UsersTableSeeder:

```php
factory(App\User::class)->make();
```

#### Executando Seeders Específicos
Para executar um Seeder específico usando o artisan, usamos o comando:

```php
php artisan db:seed --class=UsersTableSeeder
```

### Utilizando o Faker
O arquivo `config/app.php` possui o comando `'faker_locale' => 'en_US'`, substituir por 'pt_BR'. 

Podemos criar múltiplos registros facilmente usando o Factory:
```php
factory(App\User::class, 20)->create();
```

### Zerando todos os dados do banco com migrate
Ao criar todos os dados percebemos que se formos criar uma nova tabela 

```php
php artisan migrate:refresh 
```


### Populando dados da tabela `categories`
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


### Populando dados da tabela `posts`

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


<span style="font-size:30px;font-weight:bold;">Relações Polimórficas</span>


Iremos criar uma tabela de avaliações.
Ao pensar conseguimos ver o quão complexo poder ser, pois essa tabela identificará para quem é a 
avaliação no sentido de se a avaliação será para comentário, para o post ou para um usuário.
Existem N maneiras de fazer essa tarefa, criando por exemplo uma tabela que tenha o id de quem está sendo avaliado e os campos de todos que podem ser avaliados (A desvantagem seria deixar muitos campos vazios), nesse caso, usaremos a seguinte forma: 

<b>Criaremos uma tabela com nome `ratings`</b>, que guardará todas as avaliações, seja de usuários ou posts, 
o id do referenciado (se é o id do post ou o id do user) e qual o tipo do avaliado (se é post ou user).

Iremos criar o model `Rating` com:

```php
php artisan make:model Rating
```

Nossa model ficará assim precisa implementar um método no model para dizer que retorna uma tabela.
Usaremos o `morphTo`, pois ele terá apenas uma avaliação.
Ficará assim:
```php
class Rating extends Model
{
   public function ratingable()
   {
       return $this->morphTo();
   }
}
```

No model `Post` será adicionado várias avaliações para cada post.
Então será adicionado mais um método no model `Post` com o método `morphMany`, passando como parâmetro o model que ele fará referência, dessa forma:
```php
 public function ratings()
{
   return $this->morphMany('App\Model\Rating','ratingable');
}
```

Semelhantemente faremos no model `User`, pois ele faz referência a tabela de avaliações: 
```php
public function ratings()
{
   return $this->morphMany('App\Model\Rating','ratingable');
}
```
### Populando tabela com tinker

Criaremos o primeiro dado a fim de teste com o Tinker:

```php
php artisan tinker
```

Você poderá usar o banco de dados diretamente no console.
Com o comando:
```php
$post = App\Model\Post::find(1)
```
Ele irá me retornar o seguinte:
```php
    App\Model\Post {#2920
        id:1,
        title:"Sed. ",
        content: "Lorem ipsum...",
        created_at: "2018-12-06 14:00:00",
        updated_at: "2018-12-06 16:00:00"
    }
```

Agora quero criar uma avaliação para esse post, nós atribuímos a variável `$post` o valor desse Post com `id = 1`.
<br>Então usando ele podemos criar uma nova avaliação, dessa forma:

```php
$post->ratings()->create(['value'=>9])
```
Criamos uma avaliação com nota 9 para esse post, ao digitarmos esse comando ele nos retornará isso:

```php
App\Model\Rating {#2913
    value:9,
    ratingable_id:1,
    ratingable_type:"App\Model\Post",
    created_at:"2018-12-08 01:00:36",
    updated_at:"2018-12-08 01:00:36",
    id: 6
}
```

Para buscar as avaliações do post:

```php
$post->ratings
```


Semelhantemente faremos com o usuário:


```php
$user = App\Model\User::find(1)
```
Ele irá me retornar o seguinte:
```php
    App\Model\User {#2920
        id:1,
        title:"Sid. ",
        content: "Lorim epsum...",
        created_at: "2018-12-06 11:00:00",
        updated_at: "2018-12-06 12:00:00"
    }
```

```php
$user->ratings()->create(['value'=>6])
```
Criamos uma avaliação com nota 6 para esse usuario, ao digitarmos esse comando ele nos retornará isso:

```php
App\Model\Rating {#2913
    value:6,
    ratingable_id:1,
    ratingable_type:"App\Model\Post",
    created_at:"2018-12-08 01:00:36",
    updated_at:"2018-12-08 01:00:36",
    id: 7
}
```
### Avaliações

Para olharmos as avaliações usamos:

```php
$rating = App\model\Rating::find(7)
```


Que me retornará:

```php
App\Rating{#2932
    value:6,
    ratingable_id:1,
    ratingable_type:"App\Model\Post",
    created_at:"2018-12-08 01:00:36",
    updated_at:"2018-12-08 01:00:36",
    id: 7
}
```

Para olhar ao que ela se referencia, usamos `$rating->ratingable`, que irá retornar:

```php
    App\Model\User {#2933
        id:1,
        title:"Sid. ",
        content: "Lorim epsum...",
        created_at: "2018-12-06 11:00:00",
        updated_at: "2018-12-06 12:00:00"
    }
```


<span style="font-size:30px;font-weight:bold;margin:10px;">Trabalhando com tabela pivot</span>
