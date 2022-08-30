<?php

namespace Tests\Feature\api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use App\Models\Book;

class BooksControllerTest extends TestCase
{

    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_books_endpoint()
    {
        $books = Book::factory(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200);
        $response->assertJsonCount(3);

        $response->assertJson(function(AssertableJson $json) use($books)
        {
            /*$json->whereType('0.id', 'integer');
            $json->whereType('0.title', 'string');
            $json->whereType('0.isbn', 'string');*/

            $json->whereAllType([
                '0.id' => 'integer',
                '0.title' => 'string',
                '0.isbn' => 'string',
            ]);

            $json->hasAll(['0.id', '0.title', '0.isbn',]);

            $book = $books->first();

            $json->whereAll([
                '0.id' => $book->id,
                '0.title' => $book->title,
                '0.isbn' => $book->isbn,
            ]);
        });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_book_endpoint()
    {
        $book = Book::factory(1)->createOne();

        $response = $this->getJson('/api/books/' . $book->id);

        $response->assertStatus(200);

        $response->assertJson(function(AssertableJson $json) use($book)
        {
            $json->whereAllType([
                'id' => 'integer',
                'title' => 'string',
                'isbn' => 'string',
            ]);

            $json->hasAll(['id', 'title', 'isbn',])->etc();

            $json->whereAll([
                'id' => $book->id,
                'title' => $book->title,
                'isbn' => $book->isbn,
            ]);
        });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_post_book_endpoint()
    {
        $book = Book::factory(1)->makeOne()->toArray();

        $response = $this->postJson('/api/books', $book);

        $response->assertStatus(201);

        
        $response->assertJson(function(AssertableJson $json) use($book)
        {
            $json->hasAll(['title', 'isbn',])->etc();

            $json->whereAll([
                'title' => $book['title'],
                'isbn' => $book['isbn'],
            ])->etc();
        });


    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_put_book_endpoint()
    {
        Book::factory(1)->createOne();

        $book = [
            'title' => 'atualizou',
            'isbn' => '666'
        ];

        $response = $this->putJson('/api/books/1', $book);

        $response->assertStatus(200);

        
        $response->assertJson(function(AssertableJson $json) use($book)
        {
            $json->hasAll(['id','title', 'isbn', 'created_at', 'updated_at'])->etc();

            $json->whereAll([
                'title' => $book['title'],
                'isbn' => $book['isbn'],
            ])->etc();
        });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_patch_book_endpoint()
    {
        Book::factory(1)->createOne();

        $book = [
            'title' => 'atualizou'
        ];

        $response = $this->patchJson('/api/books/1', $book);

        $response->assertStatus(200);
        
        $response->assertJson(function(AssertableJson $json) use($book)
        {
            $json->hasAll(['id','title', 'isbn', 'created_at', 'updated_at'])->etc();

            $json->where('title', $book['title']);
        });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_book_endpoint()
    {
        Book::factory(1)->createOne();

        $response = $this->deleteJson('/api/books/1');

        $response->assertStatus(204);
    }
}
