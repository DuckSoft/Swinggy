<?php
/**
 * <h2>Status Code: Initialization</h2>
 * Recommended status code for your first <i>status determinator</i>. <br/>
 * Besides, if no status determinator is specified, this one will be your status code.
 * @see Swinggy::$stat
 */
const status_init = -1;
/**
 * <h2>Status Code: Fallback</h2>
 * Occur as your status when no <i>status determinator</i> meets their needs.
 * @see Swinggy::$stat
 */
const status_fallback = -2;
/**
 * <h1>Swinggy</h1>
 * A Simple Status-Based PHP Engine.
 */
class Swinggy{
    /**
     * <h2>Status Code</h2>
     * Initially set to <b>status_init</b>.
     * When method <b>ready()</b> is called, this status code will be determined by <i>status determinators</i>
     * passed as an argument in class constructor. When no status determinator satisfies, the status will be <b>status_fallback</b>.
     * @see Swinggy::ready()
     */
    public $stat = status_init;
    /**
     * <h2>Storage</h2>
     * Used as a storage between <i>performers</i>.
     * @see Swinggy::set()
     * @see Swinggy::go()
     */
    public $stor;

    private $status_determinators;
    private $performers;

    /**
     * <h2>Swinggy Constructor</h2>
     * Construct a new <b>Swinggy</b> object.
     * @param $status_determinators - the array list of <i>status determinators</i>.
     * A sample is as follows: <br/>
     * <code>[
     *  [status_init,
     *      function(){
     *          // you should always return false when in initial status
     *          // so that Swinggy will move onto next determinator
     *          return false;
     *      }],
     *  [status_foo,
     *      function(){
     *          return ...;
     *      }],
     *  [status_bar,
     *      function(){
     *          return ...;
     *      }],
     *  ...
     * ]</code><br/>
     * The <i>status determinators</i> will be stored and then used in <b>ready()</b> method.
     * @see Swinggy::ready()
     */
    public function __construct($status_determinators) {
        $this->status_determinators = $status_determinators;
    }

    /**
     * <h2>Status Determination</h2>
     * Swinggy will start from the first <i>status determinator</i> and read the return value.
     * If Swinggy encounter a <b>true</b> value, Swinggy will set the status according to the <i>status determinator</i>,
     * and then ends the status determination. If a <b>false</b> encountered, Swinggy will continue the next
     * <i>status determinator</i> till a <b>true</b> encountered or the end of <i>status determinators</i> array,
     * when status will be set as <b>status_fallback</b>.
     * @see Swinggy::__construct()
     */
    public function ready() {
        foreach ($this->status_determinators as $key => &$value) {
            if ($value()) {
                $this->stat = $key;
                return;
            }
        }
        $this->stat = status_fallback;
    }

    /**
     * <h2>Performers Ready</h2>
     * Make preparations for the <b>go()</b> method invokes inside HTML code.
     * @param $performers - the array list of <i>performers</i>.
     * A sample is as follows:
     * <code>[
     *  ["head",
     *      function($boku){
     *          ...
     *      }],
     *  ["body",
     *      function($boku){
     *          ...
     *      }],
     *  ...
     * ]</code><br/>
     * It is recommended to use <b>$boku->stat</b> with <b>switch</b> branching statement inside the <i>performer</i>.
     * You may also use <b>$boku->stor["key"]</b> to send signals and/or information to the following <i>performers</i>.
     * For a detailed usage, visit Swinggy Wiki Pages.
     */
    public function set($performers) {
        $this->performers = $performers;
    }

    /**
     * <h2>Perform Actions</h2>
     * @param $performer - the <b>key</b> value used to call your <i>performer</i>.
     * @see Swinggy::set()
     */
    public function go($performer) {
        $this->performers[$performer]($this);
    }
}
