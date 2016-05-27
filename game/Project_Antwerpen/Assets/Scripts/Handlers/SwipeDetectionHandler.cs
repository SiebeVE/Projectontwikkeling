using UnityEngine;

public class SwipeDetectionHandler : MonoBehaviour
{ 
    public float minSwipeDistanceY;
    private Vector2 startpos;

    // the factor used to determine how much the minSwipedistanceY will be
    public int dividefactor = 3;

    // Use this for initialization
    void Start()
    {
        minSwipeDistanceY = DetermineDivideFactor(Screen.height);
    }

    // Update is called once per frame
    void Update()
    {
        if (Input.touchCount != 0)
        {
            Touch t = Input.touches[0];

            switch (t.phase)
            {
                case TouchPhase.Began:
                    startpos = t.position;
                    break;
                case TouchPhase.Ended:              // finger has stopped swiping

                    // calculate the distance between the startingposition and the last position which has been registered.
                    float swipeDistanceY = (new Vector2(0, t.position.y) - new Vector2(0, startpos.y)).magnitude;

                    if (swipeDistanceY > minSwipeDistanceY)      // the distance is greater than the minimum distance
                    {
                        // some object should be triggered
                        if (Mathf.Sign(t.position.y - startpos.y) < 0) // we're swiping down
                        {
                            Camera.main.GetComponent<AnimatorHandler>().EnableAnimator(GetComponent<Animator>());
                        }
                        else
                        {
                            Camera.main.GetComponent<AnimatorHandler>().DisableAnimator(GetComponent<Animator>());
                        }

                    }
                    break;
                default:
                    break;
            }
        }
    }

    private int DetermineDivideFactor(int screenheight)
    {
        if (screenheight >= 1000)
        {
            dividefactor = 8;
        }
        else if (screenheight >= 600)
        {
            dividefactor = 4;
        }
        else if (screenheight < 600)
        {
            dividefactor = 2;
        }

        return screenheight / dividefactor;
    }
}
