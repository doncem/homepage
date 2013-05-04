<?php
namespace homepage\controller;

/**
 * Ajax request tester
 */
class HomeAjaxTest extends \ControllerTestCase {

    /**
     * Test the earliest movie I&#39;ve seen
     */
    public function testAjaxMoviesByYearPass() {
        $this->setUpController("/ajax-movies-by-year/1902", array());
        $this->assertJsonStringEqualsJsonString('{"movies":[{"id":366,"title":"Le Voyage Dans La Lune","title_en":"A Trip To The Moon","year":1902,"link":"http://www.imdb.com/title/tt0000417/"}],"countries":[[{"id":11,"country":"France"}]],"directors":[[{"id":167,"director":"Georges M\u00e9li\u00e8s"}]],"genres":[[{"id":3,"genre":"Adventure"},{"id":11,"genre":"Fantasy"},{"id":19,"genre":"Sci-Fi"},{"id":20,"genre":"Short"}]]}', $this->response);
    }

    /**
     * Test boundary
     * @expectedException \xframe\request\InvalidParameterEx
     */
    public function testAjaxMoviesByYearBadParam() {
        $this->setUpController("/ajax-movies-by-year/1901");
    }

    /**
     * Test empty param
     * @expectedException \xframe\request\InvalidParameterEx
     */
    public function testAjaxMoviesByYearEmptyParam() {
        $this->setUpController("/ajax-movies-by-year/");
    }

    /**
     * Test valid genre
     */
    public function testAjaxMoviesAndSeriesByGenrePass() {
        $this->setUpController("/ajax-movies-and-series-by-genre/musical");
        $this->assertNotEmpty($this->response);
    }

    /**
     * Test wrong genre
     */
    public function testAjaxMoviesAndSeriesByGenreWrongParam() {
        $this->setUpController("/ajax-movies-and-series-by-genre/awesomeness");
        $this->assertNotEmpty('{"error":"No such genre on the list. What are you doing?"}', $this->response);
    }

    /**
     * Test empty param
     * @expectedException \xframe\request\InvalidParameterEx
     */
    public function testAjaxMoviesAndSeriesByGenreEmptyParam() {
        $this->setUpController("/ajax-movies-and-series-by-genre/");
    }

    /**
     * Test valid directors count
     */
    public function testAjaxMoviesByDirectorCountPass() {
        $this->setUpController("/ajax-movies-by-director-count/9");
        $this->assertNotEmpty($this->response);
    }

    /**
     * Test param boundary
     * @expectedException \xframe\request\InvalidParameterEx
     */
    public function testAjaxMoviesByDirectorCountBadParam() {
        $this->setUpController("/ajax-movies-by-director-count/101");
    }

    /**
     * Test empty param
     * @expectedException \xframe\request\InvalidParameterEx
     */
    public function testAjaxMoviesByDirectorCountEmptyParam() {
        $this->setUpController("/ajax-movies-by-director-count/");
    }

    /**
     * Test valid country
     */
    public function testAjaxMoviesAndSeriesByCountryPass() {
        $this->setUpController("/ajax-movies-and-series-by-country/norway");
        $this->assertNotEmpty($this->response);
    }

    /**
     * Test invalid country
     */
    public function testAjaxMoviesAndSeriesByCountryWrongParam() {
        $this->setUpController("/ajax-movies-and-series-by-country/atlantida");
        $this->assertNotEmpty('{"error":"No such country on the list. What are you doing?"}', $this->response);
    }
}
