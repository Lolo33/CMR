/**
 * Created by Niquelesstup on 31/08/2017.
 */
hljs.initHighlightingOnLoad();
$(document).ready(function() {
    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });
});