<?php
$initital_memory_usage = memory_get_usage(true);
// register shutfown function to display the final caclulation
register_shutdown_function('shutdown');

// create test data
echo "create 100mb.bin testfile" . PHP_EOL;
system('dd if=/dev/zero of=100mb.bin bs=1024 count=102400 >/dev/null 2>&1');

// instanciate class
echo "Start of Class. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
$testClass = new TestClass();
printLine();

// call test function with unset
echo "Berfore testFunctionWithUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
$testClass->testFunctionWithUnset();
echo "After testFunctionWithUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
printLine();

// call test class without unset
echo "Berfore testFunctionWithoutUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
$testClass->testFunctionWithoutUnset();
echo "After testFunctionWithoutUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
printLine();

// display memory usage after the function calls
echo "End of Class. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
printLine();

// remove the test data
echo "delete 100mb.bin testfile" . PHP_EOL;
unlink('100mb.bin');
printLine();

/**
 * The Test Class
 * @author Eric Dorr
 */
class TestClass {
    public function testFunctionWithoutUnset() {
        global $initital_memory_usage;
        echo "Start of testFunctionWithoutUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
        $data = file_get_contents('100mb.bin');
        echo "End of testFunctionWithoutUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
    }

    public function testFunctionWithUnset() {
        global $initital_memory_usage;
        echo "Start of testFunctionWithUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
        $data = file_get_contents('100mb.bin');
        unset($data);
        echo "End of testFunctionWithUnset. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
    }
}

// some helper functions
function shutdown() {
    global $initital_memory_usage;
    echo "End of Script. Memory usage: " . (memory_get_usage(true) - $initital_memory_usage) . PHP_EOL;
}

/**
 * @return string
 */
function printLine() {
    echo str_pad("",getScreenWidth(),'#') . PHP_EOL;
}

/**
 * @return int the screen width
 */
function getScreenWidth() { 
    preg_match_all("/speed\s([0-9]+)\sbaud;\s([0-9]+)\srows;\s([0-9]+)\scolumns;/", strtolower(exec('stty -a |grep columns')), $output);
    if(is_array($output)) {
        return $output[3][0];
    }
    return 0;
}



/**
 * Test results:
 *
 * create 100mb.bin testfile
 * Start of Class. Memory usage: 0
 * ############################################################################################################################################################################################################
 * Berfore testFunctionWithUnset. Memory usage: 0
 * Start of testFunctionWithUnset. Memory usage: 0
 * End of testFunctionWithUnset. Memory usage: 0
 * After testFunctionWithUnset. Memory usage: 0
 * ############################################################################################################################################################################################################
 * Berfore testFunctionWithoutUnset. Memory usage: 0
 * Start of testFunctionWithoutUnset. Memory usage: 0
 * End of testFunctionWithoutUnset. Memory usage: 104861696
 * After testFunctionWithoutUnset. Memory usage: 0
 * ############################################################################################################################################################################################################
 * End of Class. Memory usage: 0
 * ############################################################################################################################################################################################################
 * delete 100mb.bin testfile
 * ############################################################################################################################################################################################################
 * End of Script. Memory usage: 0
 */
