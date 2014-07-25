var demoArray = [];
for (var i = 1; i <= 1000; i++) {
    demoArray.push(1 + Math.floor(Math.random() * 50));
}

$(document).ready(function() {

    var buffer = function(items, iterFunction, callback) {
        var i = 0.
        len = items.length;

        setTimeout(function() {
            var result;
            // +new Date returns the number of milliseconds   
            //((+new Date) - start < 50 => buffer of 50 milliseconds            
            for (var start = +new Date; i < len && result !== false && ((+new Date) - start < 50); i++) {
                result = iterFunction.call(items[i], items[i], i);
            }

            /* callee is a property of the arguments object. It can be used to refer to the currently executing function inside the function body of that function. This is for example useful when you don't know the name of this function, which is for example the case with anonymous functions. */
            if (i < len && result !== false) {
                setTimeout(arguments.callee, 20);
            } else {
                callback();
            }

            }, 20);
        };
        
        

        var html = '';
        buffer(demoArray, function(item) {
            html += '<li>' + item + '</li>';
        }, function() {
            $('ul').append(html);
        });
    });