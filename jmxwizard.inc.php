<?php

include_once(dirname(__FILE__) . '/../configwizardhelper.inc.php');

jmxwizard_configwizard_init();

function jmxwizard_configwizard_init()
{
    $name = "jmxwizard";
    $args = array(
        CONFIGWIZARD_NAME => $name,
        CONFIGWIZARD_TYPE => CONFIGWIZARD_TYPE_MONITORING,
        CONFIGWIZARD_DESCRIPTION => _("Monitor JMX metrics"),
        CONFIGWIZARD_DISPLAYTITLE => _("JMX Metrics"),
        CONFIGWIZARD_FUNCTION => "jmxwizard_configwizard_func",
        CONFIGWIZARD_PREVIEWIMAGE => "jmx.png",
        CONFIGWIZARD_VERSION => "1.0.0",
        CONFIGWIZARD_AUTHOR => "David Santiago",
        CONFIGWIZARD_FILTER_GROUPS => array('network'),
        CONFIGWIZARD_REQUIRES_VERSION => 500
        );
    register_configwizard($name, $args);
}


/**
 * @param string $mode
 * @param null   $inargs
 * @param        $outargs
 * @param        $result
 *
 * @return string
 */
function jmxwizard_configwizard_func($mode = "", $inargs = null, &$outargs, &$result)
{
    $wizard_name = "jmxwizard";

    // Initialize return code and output
    $result = 0;
    $output = "";

    // Initialize output args - pass back the same data we got
    $outargs[CONFIGWIZARD_PASSBACK_DATA] = $inargs;


    switch ($mode) {
        case CONFIGWIZARD_MODE_GETSTAGE1HTML:

        $jmx_hostname   = grab_array_var($inargs, "jmx_hostname", "");
        $jmx_port       = grab_array_var($inargs, "jmx_port", "");
        $jmx_path       = grab_array_var($inargs, "jmx_path", "");
        $jmx_login      = grab_array_var($inargs, "jmx_login", "");
        $jmx_password   = grab_array_var($inargs, "jmx_password", "");

        $output = '
        <h5 class="ul">' . _('JMX Configuration') . '</h5>

        <table class="table table-condensed table-no-border table-auto-width table-padded">
        <tbody>
        <tr>
        <td class="vt">
        <label>' . _('JMX Hostname') . ':</label>
        </td>
        <td>
        <input type="text" size="40" name="jmx_hostname" id="jmx_hostname" value="' . htmlentities($jmx_hostname) . '" class="textfield form-control">
        <div class="subtext">' . _('Ex: link.company.com') . '</div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <label>' . _('JMX Port') . ':</label>
        </td>
        <td>
        <input type="text" size="40" name="jmx_port" id="jmx_port" value="' . htmlentities($jmx_port) . '" class="textfield form-control">
        <div class="subtext">' . _('Ex: 50500') . '</div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <label>' . _('JMX Path') . ':</label>
        </td>
        <td>
        <input type="text" size="40" name="jmx_path" id="jmx_path" value="' . htmlentities($jmx_path) . '" class="textfield form-control">
        <div class="subtext">' . _('Ex: alfresco/jmxrmi') . '</div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <label>' . _('JMX Login') . ':</label>
        </td>
        <td>
        <input type="text" size="40" name="jmx_login" id="jmx_login" value="' . htmlentities($jmx_login) . '" class="textfield form-control">
        <div class="subtext">' . _('Ex: monitorRole') . '</div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <label>' . _('JMX Password') . ':</label>
        </td>
        <td>
        <input type="text" size="40" name="jmx_password" id="jmx_password" value="' . htmlentities($jmx_password) . '" class="textfield form-control">
        <div class="subtext">' . _('Ex: weshwesh=1') . '</div>
        </td>
        </tr>
        </tbody>
        </table>';
        break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE1DATA:

        // get variables that were passed to us
        $jmx_hostname   = grab_array_var($inargs, "jmx_hostname", "");
        $jmx_port       = grab_array_var($inargs, "jmx_port", "");
        $jmx_path       = grab_array_var($inargs, "jmx_path", "");
        $jmx_login      = grab_array_var($inargs, "jmx_login", "");
        $jmx_password   = grab_array_var($inargs, "jmx_password", "");


        // check for errors
        $errors = 0;
        $errmsg = array();
        
        if (have_value($jmx_hostname) == false)
            $errmsg[$errors++] = _("No JMX Hostname specified.");
        if (have_value($jmx_port) == false)
            $errmsg[$errors++] = _("No JMX Port specified.");
        if (have_value($jmx_path) == false)
            $errmsg[$errors++] = _("No JMX Path specified.");
        if (have_value($jmx_login) == false)
            $errmsg[$errors++] = _("No JMX Login specified.");
        if (have_value($jmx_password) == false)
            $errmsg[$errors++] = _("No JMX Password specified.");

        if ($errors > 0) {
            $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
            $result = 1;
        }

        break;

        case CONFIGWIZARD_MODE_GETSTAGE2HTML:

        $jmx_hostname                                   = grab_array_var($inargs, "jmx_hostname");
        $jmx_port                                       = grab_array_var($inargs, "jmx_port");
        $jmx_path                                       = grab_array_var($inargs, "jmx_path");
        $jmx_login                                      = grab_array_var($inargs, "jmx_login");
        $jmx_password                                   = grab_array_var($inargs, "jmx_password");

        $memory_HeapUsed                                = grab_array_var($inargs, "memory_HeapUsed", "");
        $memory_HeapUsed_critical                       = grab_array_var($inargs, "memory_HeapUsed_critical", "");
        $memory_HeapMax                                 = grab_array_var($inargs, "memory_HeapMax", "");
        $memory_HeapMax_critical                        = grab_array_var($inargs, "memory_HeapMax_critical", "");

        $memory_NonHeapUsed                             = grab_array_var($inargs, "memory_NonHeapUsed", "");
        $memory_NonHeapUsed_critical                    = grab_array_var($inargs, "memory_NonHeapUsed_critical", "");
        $memory_NonHeapMax                              = grab_array_var($inargs, "memory_NonHeapMax", "");
        $memory_NonHeapMax_critical                     = grab_array_var($inargs, "memory_NonHeapMax_critical", "");

        $threading_DeamonThreadCount                    = grab_array_var($inargs, "threading_DeamonThreadCount", "");
        $threading_DeamonThreadCount_critical           = grab_array_var($inargs, "threading_DeamonThreadCount_critical", "");
        $threading_PeakThreadCount                      = grab_array_var($inargs, "threading_PeakThreadCount", "");
        $threading_PeakThreadCount_critical             = grab_array_var($inargs, "threading_PeakThreadCount_critical", "");
        $threading_ThreadCount                          = grab_array_var($inargs, "threading_ThreadCount", "");
        $threading_ThreadCount_critical                 = grab_array_var($inargs, "threading_ThreadCount_critical", "");
        $threading_TotalStartedThreadCount              = grab_array_var($inargs, "threading_TotalStartedThreadCount", "");
        $threading_TotalStartedThreadCount_critical     = grab_array_var($inargs, "threading_TotalStartedThreadCount_critical", "");

        $os_MaxFileDescriptorCount                      = grab_array_var($inargs, "os_MaxFileDescriptorCount", "");
        $os_MaxFileDescriptorCount_critical             = grab_array_var($inargs, "os_MaxFileDescriptorCount_critical", "");
        $os_OpenFileDescriptorCount                     = grab_array_var($inargs, "os_OpenFileDescriptorCount", "");
        $os_OpenFileDescriptorCount_critical            = grab_array_var($inargs, "os_OpenFileDescriptorCount_critical", "");
        $os_SystemLoadAverage                           = grab_array_var($inargs, "os_SystemLoadAverage", "");
        $os_SystemLoadAverage_critical                  = grab_array_var($inargs, "os_SystemLoadAverage_critical", "");


        $output = '
        <input type="hidden" name="jmx_hostname" value="' . htmlentities($jmx_hostname) . '">
        <input type="hidden" name="jmx_port" value="' . htmlentities($jmx_port) . '">
        <input type="hidden" name="jmx_path" value="' . htmlentities($jmx_path) . '">
        <input type="hidden" name="jmx_login" value="' . htmlentities($jmx_login) . '">
        <input type="hidden" name="jmx_password" value="' . htmlentities($jmx_password) . '">


        <h5 class="ul">' . _('JMX Metrics') . '</h5>
        <p>' . _('Specify which metrics you\'d like to monitor.') . '</p>
        <table class="table table-condensed table-no-border table-auto-width">
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="memory_HeapUsed" name="memory_HeapUsed" ' . is_checked($memory_HeapUsed, 1) . '>
        </td>
        <td>
        <b>' . _('Memory') . '</b><br> Heap Memory <strong>Used</strong>
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="memory_HeapUsed_critical" value="' . htmlentities($memory_HeapUsed_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="memory_HeapMax" name="memory_HeapMax" ' . is_checked($memory_HeapMax, 1) . '>
        </td>
        <td>
        <b>' . _('Memory') . '</b><br> Heap Memory <strong>Max</strong>
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="memory_HeapMax_critical" value="' . htmlentities($memory_HeapMax_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="memory_NonHeapUsed" name="memory_NonHeapUsed" ' . is_checked($memory_NonHeapUsed, 1) . '>
        </td>
        <td>
        <b>' . _('Memory') . '</b><br> Non Heap Memory <strong>Used</strong>
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="memory_NonHeapUsed_critical" value="' . htmlentities($memory_NonHeapUsed_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="memory_NonHeapMax" name="memory_NonHeapMax" ' . is_checked($memory_NonHeapMax, 1) . '>
        </td>
        <td>
        <b>' . _('Memory') . '</b><br> Non Heap Memory <strong>Max</strong>
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="memory_NonHeapMax_critical" value="' . htmlentities($memory_NonHeapMax_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="threading_DeamonThreadCount" name="threading_DeamonThreadCount" ' . is_checked($threading_DeamonThreadCount, 1) . '>
        </td>
        <td>
        <b>' . _('Threading') . '</b><br> Daemon Thread Count
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="threading_DeamonThreadCount_critical" value="' . htmlentities($threading_DeamonThreadCount_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="threading_PeakThreadCount" name="threading_PeakThreadCount" ' . is_checked($threading_PeakThreadCount, 1) . '>
        </td>
        <td>
        <b>' . _('Threading') . '</b><br> Peak Thread Count
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="threading_PeakThreadCount_critical" value="' . htmlentities($threading_PeakThreadCount_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="threading_ThreadCount" name="threading_ThreadCount" ' . is_checked($threading_ThreadCount, 1) . '>
        </td>
        <td>
        <b>' . _('Threading') . '</b><br> Thread Count
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="threading_ThreadCount_critical" value="' . htmlentities($threading_ThreadCount_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="threading_TotalStartedThreadCount" name="threading_TotalStartedThreadCount" ' . is_checked($threading_TotalStartedThreadCount, 1) . '>
        </td>
        <td>
        <b>' . _('Threading') . '</b><br> Total Started Thread Count
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="threading_TotalStartedThreadCount_critical" value="' . htmlentities($threading_TotalStartedThreadCount_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="os_MaxFileDescriptorCount" name="os_MaxFileDescriptorCount" ' . is_checked($os_MaxFileDescriptorCount, 1) . '>
        </td>
        <td>
        <b>' . _('OS') . '</b><br> Max File Descriptor Count
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="os_MaxFileDescriptorCount_critical" value="' . htmlentities($os_MaxFileDescriptorCount_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="os_OpenFileDescriptorCount" name="os_OpenFileDescriptorCount" ' . is_checked($os_OpenFileDescriptorCount, 1) . '>
        </td>
        <td>
        <b>' . _('OS') . '</b><br> Open File Descriptor Count
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="os_OpenFileDescriptorCount_critical" value="' . htmlentities($os_OpenFileDescriptorCount_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>
        <tr>
        <td class="vt">
        <input type="checkbox" class="checkbox" id="os_SystemLoadAverage" name="os_SystemLoadAverage" ' . is_checked($os_SystemLoadAverage, 1) . '>
        </td>
        <td>
        <b>' . _('OS') . '</b><br> System Load Average
        <div class="pad-t5">
        <label><img src="'.theme_image('critical_small.png').'" class="tt-bind" title="'._('Critical Threshold').'"> ' . _('Above') . ':</label>
        <input size="11" name="os_SystemLoadAverage_critical" value="' . htmlentities($os_SystemLoadAverage_critical) . '" class="textfield form-control condensed" type="text">
        </div>
        </td>
        </tr>

        </table>';
        break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE2DATA:

            // Get variables that were passed to us
        $jmx_hostname                                   = grab_array_var($inargs, "jmx_hostname");
        $jmx_port                                       = grab_array_var($inargs, "jmx_port");
        $jmx_path                                       = grab_array_var($inargs, "jmx_path");
        $jmx_login                                      = grab_array_var($inargs, "jmx_login");
        $jmx_password                                   = grab_array_var($inargs, "jmx_password");

        $memory_HeapUsed                                = grab_array_var($inargs, "memory_HeapUsed", "");
        $memory_HeapUsed_critical                       = grab_array_var($inargs, "memory_HeapUsed_critical", "");
        $memory_HeapMax                                 = grab_array_var($inargs, "memory_HeapMax", "");
        $memory_HeapMax_critical                        = grab_array_var($inargs, "memory_HeapMax_critical", "");
        $memory_NonHeapUsed                             = grab_array_var($inargs, "memory_NonHeapUsed", "");
        $memory_NonHeapUsed_critical                    = grab_array_var($inargs, "memory_NonHeapUsed_critical", "");
        $memory_NonHeapMax                              = grab_array_var($inargs, "memory_NonHeapMax", "");
        $memory_NonHeapMax_critical                     = grab_array_var($inargs, "memory_NonHeapMax_critical", "");

        $threading_DeamonThreadCount                    = grab_array_var($inargs, "threading_DeamonThreadCount", "");
        $threading_DeamonThreadCount_critical           = grab_array_var($inargs, "threading_DeamonThreadCount_critical", "");
        $threading_PeakThreadCount                      = grab_array_var($inargs, "threading_PeakThreadCount", "");
        $threading_PeakThreadCount_critical             = grab_array_var($inargs, "threading_PeakThreadCount_critical", "");
        $threading_ThreadCount                          = grab_array_var($inargs, "threading_ThreadCount", "");
        $threading_ThreadCount_critical                 = grab_array_var($inargs, "threading_ThreadCount_critical", "");
        $threading_TotalStartedThreadCount              = grab_array_var($inargs, "threading_TotalStartedThreadCount", "");
        $threading_TotalStartedThreadCount_critical     = grab_array_var($inargs, "threading_TotalStartedThreadCount_critical", "");

        $os_MaxFileDescriptorCount                      = grab_array_var($inargs, "os_MaxFileDescriptorCount", "");
        $os_MaxFileDescriptorCount_critical             = grab_array_var($inargs, "os_MaxFileDescriptorCount_critical", "");
        $os_OpenFileDescriptorCount                     = grab_array_var($inargs, "os_OpenFileDescriptorCount", "");
        $os_OpenFileDescriptorCount_critical            = grab_array_var($inargs, "os_OpenFileDescriptorCount_critical", "");
        $os_SystemLoadAverage                           = grab_array_var($inargs, "os_SystemLoadAverage", "");
        $os_SystemLoadAverage_critical                  = grab_array_var($inargs, "os_SystemLoadAverage_critical", "");

            // Check for errors
        $errors = 0;
        $errmsg = array();



        if (have_value($memory_HeapUsed) == false && have_value($memory_HeapMax) == false && have_value($memory_NonHeapUsed) == false && have_value($memory_NonHeapMax) == false && have_value($threading_DeamonThreadCount) == false && have_value($threading_PeakThreadCount) == false && have_value($threading_ThreadCount) == false && have_value($threading_TotalStartedThreadCount) == false && have_value($os_MaxFileDescriptorCount) == false && have_value($os_OpenFileDescriptorCount) == false && have_value($os_SystemLoadAverage) == false)
            $errmsg[$errors++] = _("No JMX Metric checked.");

        if ($errors > 0) {
            $outargs[CONFIGWIZARD_ERROR_MESSAGES] = $errmsg;
            $result = 1;
        }

        break;


        case CONFIGWIZARD_MODE_GETSTAGE3HTML:

        $jmx_hostname                                   = grab_array_var($inargs, "jmx_hostname");
        $jmx_port                                       = grab_array_var($inargs, "jmx_port");
        $jmx_path                                       = grab_array_var($inargs, "jmx_path");
        $jmx_login                                      = grab_array_var($inargs, "jmx_login");
        $jmx_password                                   = grab_array_var($inargs, "jmx_password");

        $memory_HeapUsed                                = grab_array_var($inargs, "memory_HeapUsed");
        $memory_HeapUsed_critical                       = grab_array_var($inargs, "memory_HeapUsed_critical");
        $memory_HeapMax                                 = grab_array_var($inargs, "memory_HeapMax");
        $memory_HeapMax_critical                        = grab_array_var($inargs, "memory_HeapMax_critical");
        $memory_NonHeapUsed                             = grab_array_var($inargs, "memory_NonHeapUsed");
        $memory_NonHeapUsed_critical                    = grab_array_var($inargs, "memory_NonHeapUsed_critical");
        $memory_NonHeapMax                              = grab_array_var($inargs, "memory_NonHeapMax");
        $memory_NonHeapMax_critical                     = grab_array_var($inargs, "memory_NonHeapMax_critical");

        $threading_DeamonThreadCount                    = grab_array_var($inargs, "threading_DeamonThreadCount");
        $threading_DeamonThreadCount_critical           = grab_array_var($inargs, "threading_DeamonThreadCount_critical");
        $threading_PeakThreadCount                      = grab_array_var($inargs, "threading_PeakThreadCount");
        $threading_PeakThreadCount_critical             = grab_array_var($inargs, "threading_PeakThreadCount_critical");
        $threading_ThreadCount                          = grab_array_var($inargs, "threading_ThreadCount");
        $threading_ThreadCount_critical                 = grab_array_var($inargs, "threading_ThreadCount_critical");
        $threading_TotalStartedThreadCount              = grab_array_var($inargs, "threading_TotalStartedThreadCount");
        $threading_TotalStartedThreadCount_critical     = grab_array_var($inargs, "threading_TotalStartedThreadCount_critical");

        $os_MaxFileDescriptorCount                      = grab_array_var($inargs, "os_MaxFileDescriptorCount");
        $os_MaxFileDescriptorCount_critical             = grab_array_var($inargs, "os_MaxFileDescriptorCount_critical");
        $os_OpenFileDescriptorCount                     = grab_array_var($inargs, "os_OpenFileDescriptorCount");
        $os_OpenFileDescriptorCount_critical            = grab_array_var($inargs, "os_OpenFileDescriptorCount_critical", "");
        $os_SystemLoadAverage                           = grab_array_var($inargs, "os_SystemLoadAverage");
        $os_SystemLoadAverage_critical                  = grab_array_var($inargs, "os_SystemLoadAverage_critical");

        $output = '

        <input type="hidden" name="jmx_hostname" value="' . htmlentities($jmx_hostname) . '">
        <input type="hidden" name="jmx_port" value="' . htmlentities($jmx_port) . '">
        <input type="hidden" name="jmx_path" value="' . htmlentities($jmx_path) . '">
        <input type="hidden" name="jmx_login" value="' . htmlentities($jmx_login) . '">
        <input type="hidden" name="jmx_password" value="' . htmlentities($jmx_password) . '">

        <input type="hidden" name="memory_HeapUsed" value="' . htmlentities($memory_HeapUsed) . '">
        <input type="hidden" name="memory_HeapUsed_critical" value="' . htmlentities($memory_HeapUsed_critical) . '">
        <input type="hidden" name="memory_HeapMax" value="' . htmlentities($memory_HeapMax) . '">
        <input type="hidden" name="memory_HeapMax_critical" value="' . htmlentities($memory_HeapMax_critical) . '">
        <input type="hidden" name="memory_NonHeapUsed" value="' . htmlentities($memory_NonHeapUsed) . '">
        <input type="hidden" name="memory_NonHeapUsed_critical" value="' . htmlentities($memory_NonHeapUsed_critical) . '">
        <input type="hidden" name="memory_NonHeapMax" value="' . htmlentities($memory_NonHeapMax) . '">
        <input type="hidden" name="memory_NonHeapMax_critical" value="' . htmlentities($memory_NonHeapMax_critical) . '">

        <input type="hidden" name="threading_DeamonThreadCount" value="' . htmlentities($threading_DeamonThreadCount) . '">
        <input type="hidden" name="threading_DeamonThreadCount_critical" value="' . htmlentities($threading_DeamonThreadCount_critical) . '">
        <input type="hidden" name="threading_PeakThreadCount" value="' . htmlentities($threading_PeakThreadCount) . '">
        <input type="hidden" name="threading_PeakThreadCount_critical" value="' . htmlentities($threading_PeakThreadCount_critical) . '">
        <input type="hidden" name="threading_ThreadCount" value="' . htmlentities($threading_ThreadCount) . '">
        <input type="hidden" name="threading_ThreadCount_critical" value="' . htmlentities($threading_ThreadCount_critical) . '">
        <input type="hidden" name="threading_TotalStartedThreadCount" value="' . htmlentities($threading_TotalStartedThreadCount) . '">
        <input type="hidden" name="threading_TotalStartedThreadCount_critical" value="' . htmlentities($threading_TotalStartedThreadCount_critical) . '">

        <input type="hidden" name="os_MaxFileDescriptorCount" value="' . htmlentities($os_MaxFileDescriptorCount) . '">
        <input type="hidden" name="os_MaxFileDescriptorCount_critical" value="' . htmlentities($os_MaxFileDescriptorCount_critical) . '">
        <input type="hidden" name="os_OpenFileDescriptorCount" value="' . htmlentities($os_OpenFileDescriptorCount) . '">
        <input type="hidden" name="os_OpenFileDescriptorCount_critical" value="' . htmlentities($os_OpenFileDescriptorCount_critical) . '">
        <input type="hidden" name="os_SystemLoadAverage" value="' . htmlentities($os_SystemLoadAverage) . '">
        <input type="hidden" name="os_SystemLoadAverage_critical" value="' . htmlentities($os_SystemLoadAverage_critical) . '">
        
        ';
        break;

        case CONFIGWIZARD_MODE_VALIDATESTAGE3DATA:

        break;

        case CONFIGWIZARD_MODE_GETFINALSTAGEHTML:

        $output = '

        ';
        break;

        case CONFIGWIZARD_MODE_GETOBJECTS:

        $jmx_hostname                                   = grab_array_var($inargs, "jmx_hostname", "");
        $jmx_port                                       = grab_array_var($inargs, "jmx_port", "");
        $jmx_path                                       = grab_array_var($inargs, "jmx_path", "");
        $jmx_login                                      = grab_array_var($inargs, "jmx_login", "");
        $jmx_password                                   = grab_array_var($inargs, "jmx_password", "");

        $memory_HeapUsed                                = grab_array_var($inargs, "memory_HeapUsed", "");
        $memory_HeapUsed_critical                       = grab_array_var($inargs, "memory_HeapUsed_critical", "");
        $memory_HeapMax                                 = grab_array_var($inargs, "memory_HeapMax", "");
        $memory_HeapMax_critical                        = grab_array_var($inargs, "memory_HeapMax_critical", "");
        $memory_NonHeapUsed                             = grab_array_var($inargs, "memory_NonHeapUsed", "");
        $memory_NonHeapUsed_critical                    = grab_array_var($inargs, "memory_NonHeapUsed_critical", "");
        $memory_NonHeapMax                              = grab_array_var($inargs, "memory_NonHeapMax", "");
        $memory_NonHeapMax_critical                     = grab_array_var($inargs, "memory_NonHeapMax_critical", "");

        $threading_DeamonThreadCount                    = grab_array_var($inargs, "threading_DeamonThreadCount", "");
        $threading_DeamonThreadCount_critical           = grab_array_var($inargs, "threading_DeamonThreadCount_critical", "");
        $threading_PeakThreadCount                      = grab_array_var($inargs, "threading_PeakThreadCount", "");
        $threading_PeakThreadCount_critical             = grab_array_var($inargs, "threading_PeakThreadCount_critical", "");
        $threading_ThreadCount                          = grab_array_var($inargs, "threading_ThreadCount", "");
        $threading_ThreadCount_critical                 = grab_array_var($inargs, "threading_ThreadCount_critical", "");
        $threading_TotalStartedThreadCount              = grab_array_var($inargs, "threading_TotalStartedThreadCount", "");
        $threading_TotalStartedThreadCount_critical     = grab_array_var($inargs, "threading_TotalStartedThreadCount_critical", "");

        $os_MaxFileDescriptorCount                      = grab_array_var($inargs, "os_MaxFileDescriptorCount", "");
        $os_MaxFileDescriptorCount_critical             = grab_array_var($inargs, "os_MaxFileDescriptorCount_critical", "");
        $os_OpenFileDescriptorCount                     = grab_array_var($inargs, "os_OpenFileDescriptorCount", "");
        $os_OpenFileDescriptorCount_critical            = grab_array_var($inargs, "os_OpenFileDescriptorCount_critical", "");
        $os_SystemLoadAverage                           = grab_array_var($inargs, "os_SystemLoadAverage", "");
        $os_SystemLoadAverage_critical                  = grab_array_var($inargs, "os_SystemLoadAverage_critical", "");

        if (have_value($memory_HeapUsed_critical) == true)
            $memory_HeapUsed_critical = "-c $memory_HeapUsed_critical";

        if (have_value($memory_HeapMax_critical) == true)
            $memory_HeapMax_critical = "-c $memory_HeapMax_critical";

        if (have_value($memory_NonHeapUsed_critical) == true)
            $memory_NonHeapUsed_critical = "-c $memory_NonHeapUsed_critical";

        if (have_value($memory_NonHeapMax_critical) == true)
            $memory_NonHeapMax_critical = "-c $memory_NonHeapMax_critical";

        if (have_value($threading_DeamonThreadCount_critical) == true)
            $threading_DeamonThreadCount_critical = "-c $threading_DeamonThreadCount_critical";

        if (have_value($threading_PeakThreadCount_critical) == true)
            $threading_PeakThreadCount_critical = "-c $threading_PeakThreadCount_critical";

        if (have_value($threading_ThreadCount_critical) == true)
            $threading_ThreadCount_critical = "-c $threading_ThreadCount_critical";

        if (have_value($threading_TotalStartedThreadCount_critical) == true)
            $threading_TotalStartedThreadCount_critical = "-c $threading_TotalStartedThreadCount_critical";

        if (have_value($os_MaxFileDescriptorCount_critical) == true)
            $os_MaxFileDescriptorCount_critical = "-c $os_MaxFileDescriptorCount_critical";

        if (have_value($os_OpenFileDescriptorCount_critical) == true)
            $os_OpenFileDescriptorCount_critical = "-c $os_OpenFileDescriptorCount_critical";

        if (have_value($os_SystemLoadAverage_critical) == true)
            $os_SystemLoadAverage_critical = "-c $os_SystemLoadAverage_critical";


        $objs = array();

        if (!host_exists($jmx_hostname)) {
            $objs[] = array(
                "type" => OBJECTTYPE_HOST,
                    "use" => "generic-host", // A CHANGER PLUS TARD !
                    "host_name" => $jmx_hostname,
                    "address" => $jmx_hostname,
                    "jmx_hostname" => $jmx_hostname,
                    "icon_image" => "network_node.png",
                    "statusmap_image" => "network_node.png",
                    );
        }

        if (have_value($memory_HeapUsed) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Memory:HeapMemoryUsage:Used",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Memory!" . "HeapMemoryUsage!" . $jmx_login . '!' . $jmx_password . '!' . "-K used " . $memory_HeapUsed_critical,
                );
        }

        if (have_value($memory_HeapMax) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Memory:HeapMemoryUsage:Max",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Memory!" . "HeapMemoryUsage!" . $jmx_login . '!' . $jmx_password . '!' . "-K max " . $memory_HeapMax_critical,
                );
        }

        if (have_value($memory_NonHeapUsed) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Memory:NonHeapMemoryUsage:Used",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Memory!" . "NonHeapMemoryUsage!" . $jmx_login . '!' . $jmx_password . '!' . "-K used " . $memory_NonHeapUsed_critical,
                );
        }

        if (have_value($memory_NonHeapMax) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Memory:NonHeapMemoryUsage:Max",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Memory!" . "NonHeapMemoryUsage!" . $jmx_login . '!' . $jmx_password . '!' . "-K max " . $memory_NonHeapMax_critical,
                );
        }

        if (have_value($threading_DeamonThreadCount) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Threading:DaemonThreadCount",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Threading!" . "DaemonThreadCount!" . $jmx_login . '!' . $jmx_password . '!' . $threading_DeamonThreadCount_critical,
                );
        }

        if (have_value($threading_PeakThreadCount) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Threading:PeakThreadCount",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Threading!" . "PeakThreadCount!" . $jmx_login . '!' . $jmx_password . '!' . $threading_PeakThreadCount_critical,
                );
        }

        if (have_value($threading_ThreadCount) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Threading:ThreadCount",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Threading!" . "ThreadCount!" . $jmx_login . '!' . $jmx_password . '!' . $threading_ThreadCount_critical,
                );
        }

        if (have_value($threading_TotalStartedThreadCount) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:Threading:TotalStartedThreadCount",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "Threading!" . "TotalStartedThreadCount!" . $jmx_login . '!' . $jmx_password . '!' . $threading_TotalStartedThreadCount_critical,
                );
        }

        if (have_value($os_MaxFileDescriptorCount) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:OS:MaxFileDescriptorCount",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "OperatingSystem!" . "MaxFileDescriptorCount!" . $jmx_login . '!' . $jmx_password . '!' . $os_MaxFileDescriptorCount_critical,
                );
        }

        if (have_value($os_OpenFileDescriptorCount) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:OS:OpenFileDescriptorCount",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "OperatingSystem!" . "OpenFileDescriptorCount!" . $jmx_login . '!' . $jmx_password . '!' . $os_OpenFileDescriptorCount_critical,
                );
        }

        if (have_value($os_SystemLoadAverage) == true) {
            $objs[] = array(
                "type" => OBJECTTYPE_SERVICE,
                "host_name" => $jmx_hostname,
                "service_description" => "JMX:OS:SystemLoadAverage",
                "check_command" => "check_jmx!" . $jmx_port . '!' . $jmx_path . '!' . "OperatingSystem!" . "SystemLoadAverage!" . $jmx_login . '!' . $jmx_password . '!' . $os_SystemLoadAverage_critical,
                );
        }

            // return the object definitions to the wizard
        $outargs[CONFIGWIZARD_NAGIOS_OBJECTS] = $objs;

        break;

        default:
        break;
    }

    return $output;
}
