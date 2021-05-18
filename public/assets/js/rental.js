$(document).ready(function () {

    $('#rental-image').click(function () {
        //je récupère le numéro des futurs champs que je vais créer
        const index = +$('#widget-counter').val();// on récupère la valeur du champs de départ ->0...
        //je récupère le prototype des entrées
        const tmpl = $('#rental_images').data('prototype').replace(/__name__/g, index);
        //j'injecte ce code au sein de la div
        $('#rental_images').append(tmpl);

        $('#widget-counter').val(index + 1); // ... et on rejoute 1
        handleDeleteButtons();

    });

    function handleDeleteButtons() {
        $('button[data-action="delete"]').click(function () {
            const target = this.dataset.target;
            $(target).remove();

            //console.log(target);
        });
    }

    function updateCounter() {
        const count = +$('#rental_images div.form-group').length;

        $('#widget-counter').val(count);
    }

    updateCounter();

    handleDeleteButtons();

});