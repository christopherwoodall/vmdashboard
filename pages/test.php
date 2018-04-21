<?php
require('header.php');
require('navigation.php'); ?>


<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Domains</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Domain List</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li><a href="#">Settings 1</a>
                  </li>
                  <li><a href="#">Settings 2</a>
                  </li>
                </ul>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">




<?php
$xmlstr = "
<domain type='kvm'>
  <name>newVM</name>
  <uuid>879b2676-b680-4517-9bcd-2c259abfd865</uuid>
  <memory unit='KiB'>2097152</memory>
  <currentMemory unit='KiB'>2097152</currentMemory>
  <vcpu placement='static'>4</vcpu>
  <os>
    <type arch='x86_64' machine='pc-i440fx-xenial'>hvm</type>
    <boot dev='hd'/>
    <boot dev='cdrom'/>
    <boot dev='network'/>
  </os>
  <clock offset='localtime'/>
  <on_poweroff>destroy</on_poweroff>
  <on_reboot>restart</on_reboot>
  <on_crash>destroy</on_crash>
  <devices>
    <emulator>/usr/bin/kvm-spice</emulator>
    <disk type='file' device='cdrom'>
      <driver name='qemu' type='raw'/>
      <source file='/var/www/html/openVM/uploads/iso_uploads/ubuntu-16.04.4-server-amd64.iso'/>
      <target dev='hda' bus='ide'/>
      <readonly/>
      <address type='drive' controller='0' bus='0' target='0' unit='0'/>
    </disk>
    <disk type='file' device='disk'>
      <driver name='qemu' type='qcow2'/>
      <source file='/var/lib/libvirt/images/newVM.qcow2'/>
      <target dev='vda' bus='virtio'/>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x05' function='0x0'/>
    </disk>
    <disk type='file' device='disk'>
      <driver name='qemu' type='qcow2'/>
      <source file='/var/lib/libvirt/images/newVM2.qcow2'/>
      <target dev='vdb' bus='virtio'/>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x06' function='0x0'/>
    </disk>
    <controller type='ide' index='0'>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x01' function='0x1'/>
    </controller>
    <controller type='usb' index='0'>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x01' function='0x2'/>
    </controller>
    <controller type='pci' index='0' model='pci-root'/>
    <interface type='direct'>
      <mac address='52:54:00:6d:a3:55'/>
      <source dev='eno1' mode='bridge'/>
      <model type='virtio'/>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x03' function='0x0'/>
    </interface>
    <interface type='network'>
      <mac address='52:54:00:c5:08:1e'/>
      <source network='default'/>
      <model type='rtl8139'/>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x07' function='0x0'/>
    </interface>
    <interface type='direct'>
      <mac address='52:54:00:6d:a3:56'/>
      <source dev='eno2' mode='bridge'/>
      <model type='virtio'/>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x08' function='0x0'/>
    </interface>
    <input type='mouse' bus='ps2'/>
    <input type='keyboard' bus='ps2'/>
    <graphics type='vnc' port='-1' autoport='yes'/>
    <video>
      <model type='cirrus' vram='16384' heads='1'/>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x02' function='0x0'/>
    </video>
    <memballoon model='virtio'>
      <address type='pci' domain='0x0000' bus='0x00' slot='0x04' function='0x0'/>
    </memballoon>
  </devices>
</domain>
";

$xml = new SimpleXMLElement($xmlstr);

//add id="6" to <domain> tag
$xml->addAttribute('id', '6');

//lets get domain name
$domain_name = $xml->name;
echo "Domain Name: " . $domain_name . "<br>";

//lets get the memory unit
$unit = $xml->memory['unit'];
echo "Memory Unit: " . $unit . "<br>";


//lets get second boot device
$second = $xml->os->boot[1][dev];
echo "Second boot device: " . $second . "<br>";

//get all boot devices
//$boot = $xml->os->getChilderen();
//var_dump($boot);


//add a new interface
$interface = $xml->devices->addChild('interface');
$interface->addAttribute('type','direct');
$mac = $interface->addChild('mac');
$mac->addAttribute('address', '52:54:00:66:55:44');
$source = $interface->addChild('source');
$source->addAttribute('dev','eno3');
$source->addAttribute('mode','bridge');
$model = $interface->addChild('model');
$model->addAttribute('type','virtio');

//get xpath of interface, returns array of information
$path = $xml->xpath('//interface');
for ($i = 0; $i < sizeof($path); $i++) {
$interface_type = $xml->devices->interface[$i][type];
$interface_mac = $xml->devices->interface[$i]->mac[address];
if ($interface_type == "network") {
  $source_network = $xml->devices->interface[$i]->source[network];
}
if ($interface_type == "direct") {
  $source_dev = $xml->devices->interface[$i]->source[dev];
  $source_mode = $xml->devices->interface[$i]->source[mode];
}
$interface_model = $xml->devices->interface[$i]->model[type];


echo "Interface type: " . $interface_type . "<br>";
echo "Interface mac: " . $interface_mac . "<br>";
if ($interface_type == "network") {
  echo "Source network: " . $source_network . "<br>";
}
if ($interface_type == "direct") {
  echo "Source device: " . $source_dev . "<br>";
  echo "Source mode: " . $source_mode . "<br>";
}
echo "Interface model: " . $interface_model . "<br>";
}


// get_xpath($domain, '//domain/os/type/@arch', false);
echo "<textarea>";
echo $xml->asXML();
echo "</textarea>";








echo "<hr>";
$uuid = "879b2676-b680-4517-9bcd-2c259abfd865";
$domName = $lv->domain_get_name_by_uuid($uuid);
$domXML = $lv->domain_get_xml($domName);
$domXML = new SimpleXMLElement($domXML);
?>

<br/><br/><br/>
<!-- add network here -->
<h4>Network Information</h4>
<?php
/* Network interface information */
$path = $domXML->xpath('//interface');
if (!empty($path)) {
  echo "<div class='table-responsive'>" .
    "<table class='table'>" .
    "<tr>" .
    "<th>Type</th>" .
    "<th>MAC</th>" .
    "<th>Source</th>" .
    "<th>Mode</th>" .
    "<th>Model</th>" .
    "</tr>" .
    "<tbody>";

  for ($i = 0; $i < sizeof($path); $i++) {
    $interface_type = $xml->devices->interface[$i][type];
    $interface_mac = $xml->devices->interface[$i]->mac[address];
    if ($interface_type == "network") {
      $source_network = $xml->devices->interface[$i]->source[network];
    }
    if ($interface_type == "direct") {
      $source_dev = $xml->devices->interface[$i]->source[dev];
      $source_mode = $xml->devices->interface[$i]->source[mode];
    }
    $interface_model = $xml->devices->interface[$i]->model[type];

    $mac_encoded = base64_encode($tmp[$i]['mac']); //used to send via $_GET
    if ($interface_type == "network") {
      echo "<tr>" .
        "<td>$interface_type</td>" .
        "<td>$interface_mac</td>" .
        "<td>$source_network</td>" .
        "<td></td>" .
        "<td>$interface_model</td>" .
        "</tr>";
    }
    if ($interface_type == "direct") {
      echo "<tr>" .
        "<td>$interface_type</td>" .
        "<td>$interface_mac</td>" .
        "<td>$source_dev</td>" .
        "<td>$source_mode</td>" .
        "<td>$interface_model</td>" .
        "</tr>";
    }
  }
  echo "</tbody></table></div>";
} else {
  echo '<hr><p>Domain doesn\'t have any network devices</p>';
}
?>



</div>
</div>
</div>
</div>
</div>
</div>
<!-- /page content -->





<?php
require('footer.php');
?>