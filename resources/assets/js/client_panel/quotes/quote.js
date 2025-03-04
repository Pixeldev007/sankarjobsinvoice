document.addEventListener("turbo:load", loadQuote);

function loadQuote() {
    initializeSelect2Quote();
}

function initializeSelect2Quote() {
    if (!select2NotExists("#status_filter")) {
        return false;
    }
    removeSelect2Container(["#status_filter"]);

    $("#status_filter").select2({
        placeholder: "All",
    });

    if ($("#status").val() == "") {
        $("#status_filter").val(5).trigger("change");
    }
}

listenClick("#resetFilter", function () {
    $("#status_filter").val(5).trigger("change");
    $("#status_filter").select2({
        placeholder: "All",
    });
});

// delete client quote record
listenClick(".client-quote-delete-btn", function (event) {
    event.preventDefault();
    let quoteId = $(this).attr("data-id");
    deleteItem(route("client.quotes.destroy", quoteId), Lang.get("js.quote"));
});
