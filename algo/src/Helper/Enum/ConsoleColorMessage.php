<?php

namespace FizBuz\Helper\Enum;

enum ConsoleColorMessage: string
{

    case red = "\033[31m";
    case green = "\033[32m";
    case yellow = "\033[33m";
    case blue = "\033[34m";
    case default = "\033[0m";

}