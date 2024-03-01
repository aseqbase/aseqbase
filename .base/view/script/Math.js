Math.biggest        = Number.POSITIVE_INFINITY;
Math.smallest       = Number.NEGATIVE_INFINITY;


Math.isEven         = function() 
{
    for(const val of expand.apply(null,arguments)) 
        if(val == 0 || val%2 != 0)
            return false;
    return true;
}

Math.isOdd         = function() 
{
    for(const val of expand.apply(null,arguments)) 
        if(val == 0 || val%2 == 0)
            return false;
    return true;
}

Math.values             = function()
{
    return Array.from(expand.apply(null,arguments));
}

Math.count          = function() 
{
    return Math.values.apply(null,arguments).length;
}

Math.frequent       = function() 
{
    let arr = [];
    for(let val of expand.apply(null,arguments)) 
    {
        const ind = arr.findIndex(v=>v[0] == val);
        if(ind<0)
            arr.push([val,1]);
        else arr[ind][1] += 1;
    }
    return arr;
}

Math.sort           = function()
{
    return Math.values.apply(null,arguments).sort((a, b)=>a-b);
}

Math.distinct       = function()
{
    return Math.values.apply(null,arguments).filter((v, i, a) => a.indexOf(v) === i);
}

Math.sum            = function() 
{
    let res = 0;
    for(const val of expand.apply(null,arguments)) 
        res += val;
    return res;
}

Math.mean           = function() 
{
    return Math.sum.apply(null,arguments) / Math.count.apply(null,arguments);
}

Math.median         = function() 
{
    const arr = Math.sort.apply(null,arguments);
    const c = arr.length;
    if(Math.isOdd(c)) return arr[Math.floor(c/2)];
    const cd = Math.floor(c/2);
    return Math.mean(arr[cd-1],arr[cd]);
}

Math.mode           = function()
{
    const arr = Math.modes.apply(null,arguments);
    return Math.mean(arr);
}   

Math.modes           = function() 
{
    const arr = Math.frequent.apply(null,arguments);
    const max = Math.maximum(each(arr, a=>a[1]));
    let mds = [];
    for(let i = 0; i < arr.length; i++) 
        if(arr[i][1] === max)
            mds.push(arr[i][0]);
    return mds;
}

Math.range           = function() 
{
    return Math.maximum.apply(null,arguments) - Math.minimum.apply(null,arguments);
}

Math.minimum        = function() 
{
    let res = Math.biggest;
    for(const val of expand.apply(null,arguments)) 
        res = Math.min(res, val);
    return res;
}

Math.maximum        = function() 
{
    let res = Math.smallest;
    for(const val of expand.apply(null,arguments)) 
        res = Math.max(res,val);
    return res;
}


Math.sigma       = function(i=0, k=100, func = o=>o)
{
    let d = 0;
    for (; i < k; i++)
        d += func(i);
    return d;
}

Math.convergences       = function*()
{
    let last = 1;
    for(let arg in expand.apply(null,arguments))
    {
        for(let item in arg)
            last = (last + ((item-last) / item))/2; 
        yield last;
    }
}

Math.peaks       = function*()
{
    let args = array.apply(null,arguments);
    const len = args.length -1;
    for (let i = 1; i < len; i++)
        if (Math.Abs(args[i - 1]) < Math.abs(args[i]) && Math.abs(args[i + 1]) < Math.abs(args[i]))
            yield args[i];
}

Math.valleys       = function*()
{
    let args = array.apply(null,arguments);
    const len = args.length -1;
    for (let i = 1; i < len; i++)
        if (Math.abs(args[i - 1]) > Math.abs(args[i]) && Math.abs(args[i + 1]) > Math.abs(args[i]))
            yield args[i];
}

Math.variance       = function()
{
    const mean = Math.mean.apply(null,arguments);
    let res = 0;
    for(const val of expand.apply(null,arguments)) 
        res += Math.pow(val-mean,2);
    return res/Math.count.apply(null,arguments);
}

Math.std            = function()
{
    return Math.sqrt(Math.variance.apply(null,arguments));
}

Math.logarithm      = function() {
    if(arguments.length > 1) return Math.log(arguments[0]) / Math.log(arguments[1]??10);
    else if(isArray(arguments[0])) return Math.log(arguments[0][0]) / Math.log(arguments[0][1]??10);
    else return Math.log(arguments[0]) / Math.log(10);
}

Math.cot           = function() 
{
    return 1/Math.tan.apply(null,arguments);
}

Math.power           = function(x,b=2) 
{
    return Math.pow(x,b);
}

Math.radical         = function(x,b=2) 
{
    return Math.pow(x,1/b);
}

Math.decimals        = function(num, dec = null)
{
    if (dec === null) {
        let s = "" + (+num);
        let groups = /(?:\.(\d+))|(?:e([+\-]\d+))$/gi.exec(s);
        return !groups ? 0 : Math.max(0, (groups[1] == '0' ? 0 : (groups[1] || '').length) - (groups[2] || 0));
    }
    dec = parseInt(dec);
    const d = parseInt(10**dec);
    return Number((Math.round(num * d) / d).toFixed(dec));
}

Math.slice			= function(slicesNumber, x = 100, y = 100, minX=null, minY = null)
{
	if (minX == null) minX = x/10
	if (minY == null) minY = y/10;
	
	let xLength = 1;
	let yLength = 1;
	let xSize = x / xLength;
	let ySize = y / yLength;

	while (xLength * yLength < slicesNumber)
	{
		if (xSize < minX && ySize < minY) return {x:minX,y:minY};
		else if (ySize < minY)
		{
			yLength = Math.max(1, yLength - 1);
			xLength = Math.min(slicesNumber, xLength + 1);
		}
		else if (xSize < minX)
		{
			xLength = Math.max(1, xLength - 1);
			yLength = Math.min(slicesNumber, yLength + 1);
		}
		else if (xLength > yLength)
		{
			yLength = Math.min(slicesNumber, yLength + 1);
		}
		else
		{
			xLength = Math.min(slicesNumber, xLength + 1);
		}
		xSize = x / xLength;
		ySize = y / yLength;
	}
	return {x:Math.min(xSize, x),y:Math.min(ySize, y)};
}
