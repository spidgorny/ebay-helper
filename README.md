# ebay-helper
Analyze past prices for the item you wish to purchase

# Idea

The idea of this project is to get more insights on the *price* of the ebay listing you are about to purchase.
 More specifically we want to know what was the average final price for the item so that we can make more informed decision - what is the maximal reasonable bid price.

# Implementation

We will define a search query (like "(z3,z3+,z3plus,z3 plus,z4)") and retrieve the sold items prices and calculate the average. Then we retrieve the ongoing listing and calculate the percentage of the price relative to the average.

For example: 100% indicates exactly the average price, 90% is a good bet, 150% is overpriced.

# Code

Written in PHP, uses ebay-sdk-php to talk to ebay. CLI user interface.

You need to register at http://developer.ebay.com/ and obtain API keys which you put into `configuation.php` file (not part of this distribution).

# Status

Project just started but it's usable already. See index.php and try to change parameters.

# Alternatives

* [BidVoy.net](https://bidvoy.net/z3%2B/9355)

# Output

Here you can see that the "Sony Xperia Z3+" phone with ID 292140181380 is still 5 EUR cheaper than average sell price for that phone. You should aim to bid the item until it reaches 100% of the average price.

<pre>
Sold:
+---------+-------+---------+
| name    | count | price   |
+---------+-------+---------+
| compact | 52    | 65.92   |
| Z4      | 4     | 168     |
| Z3      | 42    | 110     |
| rest    | 2     | 134.495 |
+---------+-------+---------+
Ending:
+---------+-------+---------+--------+
| name    | count | price   | diff   |
+---------+-------+---------+--------+
| compact | 21    | 51      | -14.92 |
| Z4      | 2     | 142     | -26    |
| Z3      | 25    | 66      | -44    |
| rest    | 2     | 246.495 | +112   |
+---------+-------+---------+--------+
Ending:
compact (21)
=======
+--------------+------------+------------+------------+------------+------------+------------+--------------------------------------------------------------------------+
| itemId       | currencyId | price      | diff       | diff%      | bids       | remaining  | title                                                                    |
+--------------+------------+------------+------------+------------+------------+------------+--------------------------------------------------------------------------+
| 272695361485 | EUR        | 114.01     | 48.09      | 172.95     | 10         | +00d 02h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 222537036935 | EUR        | 90         | 24.08      | 136.53     | 0          | +00d 02h   | Sony  Xperia Z3 Compact D5803 - 16GB - Grün (Ohne Simlock) Smartphone   |
| 162536221301 | EUR        | 89         | 23.08      | 135.01     | 9          | +00d 03h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 162539046758 | EUR        | 119        | 53.08      | 180.52     | 1          | +00d 04h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 132218054635 | EUR        | 180        | 114.08     | 273.06     | 0          | +00d 04h   | Sony Z3 compact Xperia weiss Kratzfrei! OVP Top Zustand Leistung 4,6"    |
| 352078651201 | EUR        | 195        | 129.08     | 295.81     | 0          | +00d 04h   | Sony  Xperia Z3 Compact D5803 - 16GB - Grün (Ohne Simlock) Smartphone   |
| 182600625284 | EUR        | 13.5       | -52.42     | 20.48      | 5          | +00d 06h   | Sony Xperia Z3 Compact D5803 Defekt                                      |
| 162537176460 | EUR        | 150        | 84.08      | 227.55     | 0          | +00d 20h   | Sony  Xperia Z3 Compact D5803 - 16GB - Orange (Ohne Simlock) Smartphone  |
| 182601464340 | EUR        | 15.5       | -50.42     | 23.51      | 4          | +00d 21h   | Xperia Z3 Compact (Touchscreen defekt)                                   |
| 263021072195 | EUR        | 25.48      | -40.44     | 38.65      | 3          | +00d 21h   | sony xperia z3 compact defekt                                            |
| 322539009532 | EUR        | 26.5       | -39.42     | 40.20      | 12         | +01d 03h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 272691144910 | EUR        | 69         | 3.08       | 104.67     | 0          | +01d 03h   | Sony  Xperia Z3 Compact D5803 - 16GB - Weiß (Ohne Simlock) Smartphone   |
| 322541247650 | EUR        | 11.5       | -54.42     | 17.45      | 4          | +01d 03h   | Sony  Xperia Z3 Compact schwarz *defekt*                                 |
| 332250849836 | EUR        | 59.99      | -5.93      | 91.00      | 0          | +01d 17h   | sony xperia z3 compact                                                   |
| 122535146101 | EUR        | 39         | -26.92     | 59.16      | 0          | +01d 18h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 222532573219 | EUR        | 77.77      | 11.85      | 117.98     | 2          | +01d 22h   | Sony Xperia Z3 Compact D5803 - Schwarz - Smartphone                      |
| 302337797927 | EUR        | 30.5       | -35.42     | 46.27      | 2          | +02d 01h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 122523501570 | EUR        | 49.9       | -16.02     | 75.70      | 0          | +02d 02h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
| 232357938004 | EUR        | 19         | -46.92     | 28.82      | 5          | +02d 02h   | Sony Xperia Z3 Compact für Bastler Posten 1                             |
| 232357947005 | EUR        | 10.5       | -55.42     | 15.93      | 2          | +02d 03h   | Sony Xperia Z3 Compact für Bastler Posten 6                             |
| 201944673251 | EUR        | 51         | -14.92     | 77.37      | 3          | +02d 04h   | Sony  Xperia Z3 Compact D5803 - 16GB - Schwarz (Ohne Simlock) Smartphone |
+--------------+------------+------------+------------+------------+------------+------------+--------------------------------------------------------------------------+
Z4 (2)
==
+--------------+------------+------------+------------+------------+------------+------------+-------------------------------------------------------------------+
| itemId       | currencyId | price      | diff       | diff%      | bids       | remaining  | title                                                             |
+--------------+------------+------------+------------+------------+------------+------------+-------------------------------------------------------------------+
| 292140181380 | EUR        | 163        | -5         | 97.02      | 10         | +00d 01h   | Sony  Xperia Z3+, 32GB, Schwarz, Ohne Simlock, mit Restgarantie,  |
| 292136071912 | EUR        | 121        | -47        | 72.02      | 4          | +00d 06h   | Sony  Xperia Z3+ E6553 - 32GB - Schwarz (Ohne Simlock) Smartphone |
+--------------+------------+------------+------------+------------+------------+------------+-------------------------------------------------------------------+
Z3 (25)
==
+--------------+------------+------------+------------+------------+------------+------------+---------------------------------------------------------------------------------+
| itemId       | currencyId | price      | diff       | diff%      | bids       | remaining  | title                                                                           |
+--------------+------------+------------+------------+------------+------------+------------+---------------------------------------------------------------------------------+
| 112431011743 | EUR        | 210        | 100        | 190.91     | 0          | +00d 00h   | Sony xperia z3                                                                  |
| 132215484518 | EUR        | 50         | -60        | 45.45      | 1          | +00d 00h   | Sony  Xperia Z3 D6603 - 16GB - Weiß (Ohne Simlock) Smartphone                  |
| 302331013400 | EUR        | 131.55     | 21.55      | 119.59     | 22         | +00d 01h   | Sony Xperia Z3 D6603 weiß 16GB LTE, Android 6.0.1, mit OVP, TOP                |
| 263019774027 | EUR        | 66         | -44        | 60.00      | 17         | +00d 02h   | Sony Xperia Z3 16 gb                                                            |
| 282506432702 | EUR        | 22.5       | -87.5      | 20.45      | 8          | +00d 04h   | Sony Xperia Z3 D6603 Smartphone 5,2 Zoll schwarz DEFEKT #1450                   |
| 272702111839 | EUR        | 125        | 15         | 113.64     | 27         | +00d 04h   | Sony  Xperia Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone                      |
| 152568183921 | EUR        | 67.85      | -42.15     | 61.68      | 19         | +00d 04h   | Sony  Xperia Z3 Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone                   |
| 252966900309 | EUR        | 57         | -53        | 51.82      | 15         | +00d 05h   | Sony  Xperia Z3 D6603 - 16GB - Copper gebraucht Kupfer Gold                     |
| 122533748299 | EUR        | 21.5       | -88.5      | 19.55      | 11         | +00d 20h   | Sony  Xperia Z3 D6603 - 16GB - Weiß (Ohne Simlock) Smartphone                  |
| 192204350119 | EUR        | 81         | -29        | 73.64      | 7          | +01d 00h   | Sony  Xperia Z3 D6603 - 16GB - Copper (Ohne Simlock) Smartphone                 |
| 222533758117 | EUR        | 130        | 20         | 118.18     | 0          | +01d 00h   | Sony xperia z3 d6603 Black 16gb                                                 |
| 292141113961 | EUR        | 75         | -35        | 68.18      | 0          | +01d 01h   | Sony Xperia Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone                       |
| 272700512380 | EUR        | 149.99     | 39.99      | 136.35     | 0          | +01d 02h   | Sony Z3 Handy - D6603 - schwarz ohne Simlock - mit OVP                          |
| 222528319176 | EUR        | 93         | -17        | 84.55      | 16         | +01d 03h   | Sony Xperia Z3 Dual Sim D6633 Schwarz Black 16 GB Smartphone ( ohne Simlock )   |
| 222535983069 | EUR        | 39.99      | -70.01     | 36.35      | 0          | +01d 03h   | Sony  Xperia Z3  Geht scheibe Defekt und Touch ohne Funktion in OVP             |
| 132211661311 | EUR        | 22.5       | -87.5      | 20.45      | 4          | +01d 04h   | Sony  Xperia Z3 Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone                   |
| 152574547449 | EUR        | 90         | -20        | 81.82      | 0          | +01d 05h   | Sony  Xperia Z3 D6603 - 16GB - Copper (Ohne Simlock) Smartphone Kupfer OVP Top  |
| 162533623591 | EUR        | 270        | 160        | 245.45     | 0          | +01d 05h   | Sony  XPERIA Z3 Handy. Weiß.Wasserdicht. Optisch ohne Kratzer.!!!              |
| 142404350537 | EUR        | 15.5       | -94.5      | 14.09      | 3          | +01d 20h   | Sony  Xperia Z3 D6603 - 16GB - Weiß (Ohne Simlock) Smartphone Handy            |
| 322540177475 | EUR        | 10.5       | -99.5      | 9.55       | 3          | +01d 23h   | Sony  Xperia Z3 Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone defekt            |
| 122532745819 | EUR        | 45.5       | -64.5      | 41.36      | 5          | +02d 03h   | Sony  Xperia Z3 Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone                   |
| 162543587583 | EUR        | 54.31      | -55.69     | 49.37      | 0          | +02d 04h   | Sony  Xperia Z3 D6603 - 16GB - Copper (Ohne Simlock) Smartphone                 |
| 302342078354 | EUR        | 179.85     | 69.85      | 163.50     | 0          | +02d 04h   | Samsung Galaxy A5 SM-A500F - 16GB - Midnight Black (Ohne Simlock) Smartphone Z3 |
| 282509569393 | EUR        | 12         | -98        | 10.91      | 4          | +02d 05h   | Sony  Xperia Z3 Z3 - 16GB - Schwarz (Ohne Simlock) Smartphone                   |
| 361997069521 | EUR        | 25.5       | -84.5      | 23.18      | 3          | +02d 05h   | Sony Xperia Z3 16GB Weiß (Ohne Simlock) - Vom Händler #827                    |
+--------------+------------+------------+------------+------------+------------+------------+---------------------------------------------------------------------------------+
rest (2)
====
+--------------+------------+------------+------------+------------+------------+------------+----------------------------------------------------------------------------------+
| itemId       | currencyId | price      | diff       | diff%      | bids       | remaining  | title                                                                            |
+--------------+------------+------------+------------+------------+------------+------------+----------------------------------------------------------------------------------+
| 182609493564 | EUR        | 255        | 120.505    | 189.60     | 0          | +00d 01h   | 5.5 ZOLL LTE 4G HANDY UMI Z 4GB+32GB HIFI  13MP FINGERPRINT SMARTPHONE 3780mAh   |
| 182608077548 | EUR        | 237.99     | 103.495    | 176.95     | 0          | +01d 02h   | 5.5" ZOLL LTE 4G HANDY UMI Z 4GB+32GB HIFI 2*13MP FINGERPRINT SMARTPHONE 3780mAh |
+--------------+------------+------------+------------+------------+------------+------------+----------------------------------------------------------------------------------+
</pre>
