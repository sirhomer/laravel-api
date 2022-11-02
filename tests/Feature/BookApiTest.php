<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;
    /** @test */

    public function can_get_all_books()
    {

        $book = Book::factory(4)->create();
        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $book[0]->title  //verificamos que llega el primer libro
            ])->assertJsonFragment([
                'title' => $book[1]->title  //verificamos que llega el segundo libro
            ]);
    }
    /** @test */
    function can_get_one_book()
    {
        $book = Book::factory()->create();
        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title  //verificamos que llega el primer libro
            ]);
    }
    /** @test */
    function can_create_books()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'My new book'
        ])->assertJsonFragment(
            [
                'title' => 'My new book'
            ]
        );
        $this->assertDatabaseHas('books', [
            'title' => 'My new book'
        ]);
    }
    /** @test */
    function can_update_books()
    {
        $book = Book::factory()->create();
        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Edited Book'
        ])->assertJsonFragment(
            [
                'title' => 'Edited Book'
            ]
        );
        $this->assertDatabaseHas('books', [
            'title' => 'Edited Book'
        ]);
    }
    /** @test */
    function can_delete_books()
    {
        $book = Book::factory()->create();
        $this->deleteJson(route('books.destroy', $book))->assertNoContent();
        $this->assertDatabaseCount('books', 0); //vericamos
    }
}
