using UnityEngine;
using System.Collections;

public class AnimatorHandler : MonoBehaviour {

    public void DisableAnimator(Animator anim)
    {
        anim.SetBool("IsDisplayed", false);
    }

    public void EnableAnimator(Animator anim)
    {
        anim.SetBool("IsDisplayed", true);
    }
}
