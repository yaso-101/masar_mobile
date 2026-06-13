<?php

it('can render', function () {
    $contents = $this->view('login.blade.php', [
        //
    ]);

    $contents->assertSee('');
});
