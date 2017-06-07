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

# Status

Project just started. Not usable yet.
