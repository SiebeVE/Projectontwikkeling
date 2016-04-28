using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using UnityEngine;

public class Stage
{
    private string mName;
    private DateTime mBeginDate, mEndDate;

    public Stage(string name, DateTime beginDate, DateTime endDate)
    {
        mName = name;
        mBeginDate = beginDate;
        mEndDate = endDate;
    }

    #region properties
    public string Name
    {
        get { return mName; }
    }

    public DateTime BeginDate
    {
        get { return mBeginDate; }
    }

    public DateTime EndDate
    {
        get { return mEndDate; }
    }
    #endregion
}

