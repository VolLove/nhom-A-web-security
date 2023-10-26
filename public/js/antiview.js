// Just a normal eval
eval("console.log('1337')");
// Now we repat the process...
var original = eval;
var fake = function(argument) {
    // If the code to be evaluated contains 1337...
    if (argument.indexOf("1337") !== -1) {
        // ... we just execute a different code
        original("for (i = 0; i < 10; i++) { console.log(i);}");
    } else {
        original(argument);
    }
}
eval = fake;
eval("console.log('We should see this...')");
// Now we should see the execution of a for loop instead of what is expected
eval("console.log('Too 1337 for you!')");