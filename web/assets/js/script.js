$(document).ready(function () {

//////////////////////////////////////////////////
////                                          ////
////                Переменные                ////
////                                          ////
//////////////////////////////////////////////////

    var systemmessage = $("#systemmessage");
    var booklist = $("#booklist");
    var currentshelf = 1;
    var error = {};
    var virgin = 1;


///////////////////////////////////////////////
////                                       ////
////                Функции                ////
////                                       ////
///////////////////////////////////////////////

    // Создать книгу
    function createBook(bookname, author) {
        $.post("api/book/create", { bookname: bookname, author: author },
            function (data) {
                resultMessage("success", data);
                var id = data["bookid"];
                // Отрисовка новой книги
                if (!data["error"]) {
                    drawOneBook(id, bookname, author);
                }
            }, "json");
        return true;
    }

    // Добавить книгу в библиотеку
    function createBookInShelf(bookname, author, shelf) {
        $.post("api/book/createinshelf", { bookname: bookname, author: author, shelf: shelf },
            function (data) {
                resultMessage("success", data);
                var id = data["bookid"];
                // Отрисовка новой книги
                if (!data["error"]) {
                    drawOneBook(id, bookname, author);
                }
            }, "json");
        return true;
    }

    // Редактировать книгу
    function editBook(bookid, bookname, author) {
        $.post("api/book/edit", { bookid: bookid, bookname: bookname, author: author },
            function (data) {
                resultMessage("success", data);
            }, "json");
        return true;
    }

    // Удалить книгу
    function deleteBook(bookid) {
        $.post("api/book/delete", { bookid: bookid },
            function (data) {
                resultMessage("danger", data);
            }, "json");
        return true;
    }

    // Информационное сообщение
    function resultMessage(color, data) {
        var message = data["message"];
        var warninglabel = '<div class="alert alert-' + color + '" role="alert">' + message + '</div>';
        systemmessage.html(warninglabel);
        setTimeout(resultMessageClear, 3000);
        return true;
    }

    // Очистка информационного сообщения
    function resultMessageClear() {
        systemmessage.html("");
        return true;
    }

    // Запрос списка книг в библиотеке
    function getBookList(shelf) {
        $.post("api/book/list", { shelf: shelf },
            function (data) {
                drawBookTable(data);
            }, "json");
        return true;
    }

    // Запрос списка всех книг
    function getBookListAll() {
        $.post("api/book/listall", { },
            function (data) {
                drawBookTable(data);
                $("#editshelfselectbutton").remove();
                $("#deleteshelfselectbutton").remove();
                virgin = 1;
            }, "json");
        return true;
    }

    // Отрисовка таблицы книг
    function drawBookTable(data) {
        booklist.html('<table id="mytable" class="table table-bordred table-striped' +
            'tableheader"><thead><th>#</th><th>Название</th><th>Автор</th>' +
            '<th class="edit">Редактировать</th><th class="delete">Удалить</th>' +
            '</thead></table>');

        jQuery.each(data, function (id, data) {
            if (!data["error"]) {
                var bookname = data["bookname"];
                var bookauthor = data["bookauthor"];
                drawOneBook(id, bookname, bookauthor);
            } else {
                error["message"] = '"Ни одной библиотеки не найдено! Создайте новую"';
                resultMessage("success", error);
            }
        });
        return true;
    }

    // Отрисовка одной книги (лучше использовать шаблонизацию)
    function drawOneBook(id, bookname, bookauthor) {
        $('#mytable tr:last').after('<tr id="book' + id + '"><td>' + id + '</td>' +
            '<td class="bookname bookname' + id + '">' + bookname + '</td>' +
            '<td class="bookauthor bookauthor' + id + '">' + bookauthor + '</td>' +
            '<td><p class="center"><button id="tableeditbookbutton" class="btn ' +
            'btn-primary btn-xs" data-id="' + id + '" data-name="' + bookname + '"' +
            ' data-author="' + bookauthor + '" data-title="Edit" data-toggle="modal"' +
            ' data-target="#editbook" data-placement="top" rel="tooltip"><span ' +
            'class="glyphicon glyphicon-pencil"></span></button></p></td>' +
            '<td><p class="center"><button id="tabledeletebookbutton" class="btn btn-danger ' +
            'btn-xs" data-title="Delete" data-toggle="modal" data-id="' + id + '" ' +
            'data-target="#deletebook" data-placement="top" rel="tooltip"><span ' +
            'class="glyphicon glyphicon-trash"></span></button></p></td></tr>');
        return true;
    }

    // Создать библиотеку
    function createShelf(shelfname) {
        $.post("api/shelf/create", { shelfname: shelfname },
            function (data) {
                resultMessage("success", data);
            }, "json");
        return true;
    }

    // Запрос списка библиотек
    function getShelfList() {
        $.post("api/shelf/list", {},
            function (data) {
                drawShelfList(data);
            }, "json");
        return true;
    }

    // Отрисовка выпадающего списка библиотек
    function drawShelfList(data) {
        $("#shelfnameadd").val("");
        $("#bookshelflist li.shelf").remove();
        $("#bookshelflist li.divider").before('<li class="shelf" role="presentation"></li>');
        jQuery.each(data, function (id, data) {
            var shelfname = data["shelfname"];
            $('#bookshelflist li.shelf:last').after('<li id="shelf' + id + '" class="shelf" role="presentation"><a role="menuitem" tabindex="-1" href="#" data-id="' + id + '">' + shelfname + '</a></li>');
        });
        return true;
    }

    // Имя текущей библиотеки
    function getCurrentShelfName() {
        var shelfName = $("#shelf" + currentshelf + " a").text();
        $("#dropdownMenuLabel").html(shelfName);
        return true;
    }

    // Редактировать библиотеку
    function editBookShef(bookShelf, bookShelfName) {
        $.post("api/shelf/edit", { shelf: bookShelf, bookShelfName: bookShelfName },
            function (data) {
                resultMessage("success", data);
            }, "json");
        return true;
    }

    // Удалить библиотеку
    function deleteBookShelf(bookShelf) {
        $.post("api/shelf/delete", { bookShelf: bookShelf },
            function (data) {
                resultMessage("danger", data);
            }, "json");
        return true;
    }


///////////////////////////////////////////////
////                                       ////
////                События                ////
////                                       ////
///////////////////////////////////////////////

    // Добавление книги
    $(document).on("click", "#addbookheaderbutton", function () {
        $("#booknameadd").val("");
        $("#bookauthoradd").val("");
    });

    $(document).on("click", "#addbookbutton", function () {
        var bookname = $("#booknameadd").val();
        var author = $("#bookauthoradd").val();
        if (virgin == 1) {
            createBook(bookname, author);
        } else {
            createBookInShelf(bookname, author, currentshelf);
        }
        $('#addbook').modal('hide');
    });

    // Редактирование книги
    $(document).on("click", "#tableeditbookbutton", function () {
        bookId = $(this).data('id');
        var bookName = $(".bookname" + bookId).text();
        var bookAuthor = $(".bookauthor" + bookId).text();
        $('#booknameedit').val(bookName);
        $('#bookauthoredit').val(bookAuthor);
    });

    $(document).on("click", "#editbookbutton", function () {
        var bookname = $("#booknameedit").val();
        var author = $("#bookauthoredit").val();
        editBook(bookId, bookname, author);
        $('#editbook').modal('hide');
        $('#book' + bookId + ' td.bookname').html(bookname);
        $('#book' + bookId + ' td.bookauthor').html(author);
    });

    // Удаление книги
    $(document).on("click", "#tabledeletebookbutton", function () {
        bookId = $(this).data('id');
    });

    $(document).on("click", "#deletebookbutton", function () {
        deleteBook(bookId);
        $('#deletebook').modal('hide');
        $('#book' + bookId).html("");
    });

    // Отмена удаления в модальном окне
    $(document).on("click", "#cancelmodalbutton", function () {
        $('#deletebook').modal('hide');
        $('#deletebookshelfmodal').modal('hide');
    });

    // Переключение библиотеки
    $(document).on("click", ".shelf a", function (event) {
        event.preventDefault();
        currentshelf = $(this).data('id');
        var shelfName = $(this).text();
        error["message"] = 'Вы перешли к библиотеке "' + shelfName + '"';
        resultMessage("success", error);
        getBookList(currentshelf);
        getCurrentShelfName();

        if (virgin == 1) {
            virgin = 0;
            $("#bookshelflist li.divider").after('<li id="editshelfselectbutton"' +
                'role="presentation" data-toggle="modal" data-target="#editbookshelfmodal"' +
                ' data-placement="top" rel="tooltip"><a role="menuitem" tabindex="-1" href="#">' +
                'Переименовать библиотеку</a></li><li id="deleteshelfselectbutton" role="presentation" ' +
                'data-toggle="modal" data-target="#deletebookshelfmodal" data-placement="top" rel="tooltip">' +
                '<a role="menuitem" tabindex="-1" href="#">Удалить библиотеку</a></li>');
        }
    });

    // Добавление библиотеки
    $(document).on("click", "#createshelfselectbutton", function (event) {
        event.preventDefault();
        $("#shelfnameadd").val("");
        getBookList(currentshelf);
    });

    $(document).on("click", "#addbookshelfmodalbutton", function () {
        var shelfname = $("#shelfnameadd").val();
        createShelf(shelfname);
        $('#addbookshelfmodal').modal('hide');
    });

    // Обновление списка библиотек
    $(document).on("click", "#dropdownMenu1", function () {
        getShelfList();
    });

    // Редактирование библиотеки
    $(document).on("click", "#editshelfselectbutton", function (event) {
        event.preventDefault();
        var bookShelfName = $("#shelf" + currentshelf + " a").text();
        $('#bookshelfnameedit').val(bookShelfName);
    });

    $(document).on("click", "#editbookshelfmodalbutton", function () {
        var booknShelfName = $("#bookshelfnameedit").val();
        editBookShef(currentshelf, booknShelfName);
        $('#editbookshelfmodal').modal('hide');
        $("#dropdownMenuLabel").html(booknShelfName);
    });

    // Удаление библиотеки
    $(document).on("click", "#deleteshelfselectbutton", function (event) {
        event.preventDefault();
    });

    $(document).on("click", "#deletebookshelfbutton", function () {
        deleteBookShelf(currentshelf);
        $('#deletebookshelfmodal').modal('hide');
        currentshelf = 1;
        getBookList(currentshelf);
        getCurrentShelfName();
    });

    // Список всех книг
    $(document).on("click", "#allbooksselectbutton", function (event) {
        event.preventDefault();
        getBookListAll();
        $("#dropdownMenuLabel").html("Выбор библиотеки");
    });


///////////////////////////////////////////////
////                                       ////
////         Запускаемые процедуры         ////
////                                       ////
///////////////////////////////////////////////

    // Запрос таблицы книг
    getBookListAll();
    // Запрос списка библиотек
    getShelfList();

});
