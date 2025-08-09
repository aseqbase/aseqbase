<?php
enum ScreenSize: string
{
	case XXLarg = "xxlg";
	case XXLargRange = "xxlg-range";
	case XLarg = "xlg";
	case XLargRange = "xlg-range";
	case Larg = "lg";
	case LargRange = "lg-range";
	case Medium = "md";
	case MediumRange = "md-range";
	case Small = "sm";
	case SmallRange = "sm-range";
	case XSmall = "xsm";
	case XSmallRange = "xsm-range";
	case XXSmall = "xxsm";
	case XXSmallRange = "xxsm-range";
}

class SilentException extends Exception
{
}
if (!error_reporting())
	set_exception_handler(function ($exception) {
		if ($exception instanceof SilentException) {
			// Do nothing (prevent logging)
		} else {
			error_log($exception->getMessage()); // Log other exceptions
		}
	});