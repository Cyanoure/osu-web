// Copyright (c) ppy Pty Ltd <contact@ppy.sh>. Licensed under the GNU Affero General Public License v3.0.
// See the LICENCE file in the repository root for full licence text.

import Img2x from 'components/img2x';
import ProfileBannerJson from 'interfaces/profile-banner';
import { route } from 'laroute';
import * as React from 'react';
import { classWithModifiers, Modifiers } from 'utils/css';

interface Props {
  banner?: ProfileBannerJson | null;
  modifiers?: Modifiers;
}

export default function ProfileTournamentBanner({ banner, modifiers }: Props) {
  if (banner == null) return null;

  return (
    <a
      className={classWithModifiers('profile-tournament-banner', modifiers)}
      href={route('tournaments.show', { tournament: banner.tournament_id })}
    >
      <Img2x className='profile-tournament-banner__image' src={banner.image} />
    </a>
  );
}
