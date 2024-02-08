# Simple-SMA-Sunny-Home-Manager-2.0
Very simple php program to read the HM smart meter in real time.

The HM uses the LAN IGMP https://de.wikipedia.org/wiki/Internet_Group_Management_Protocol 

IP="239.12.255.254"<br/>
Port = 9522 <br/>
Be careful, there is a lot of traffic on the LAN<br/>
On the switch, it is recommended to only allow the VLAN to be read on the port to the Raspi, or to plug both directly into the router and decouple the rest of the home network. 

