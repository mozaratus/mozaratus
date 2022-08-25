
export function colorHSL(v, max=360, sat=60, lum=60, a=1) {

    let c = Math.round((v / max) * 359) % 360;

    return "hsla(" + c + ", " + sat + "%, " + lum + "%, " + a + ")";
}

export function fadeScale (
    node, { delay = 0, duration = 200, easing = x => x, baseScale = 0 }
  ) {
    const o = +getComputedStyle(node).opacity;
    const m = getComputedStyle(node).transform.match(/scale\(([0-9.]+)\)/);
    const s = m ? m[1] : 1;
    const is = 1 - baseScale;
  
    return {
      delay,
      duration,
      css: t => {
        const eased = easing(t);
        return `opacity: ${eased * o}; transform: scale(${(eased * s * is) + baseScale})`;
      }
    };
  }