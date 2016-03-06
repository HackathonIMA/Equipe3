class Interaction < ActiveRecord::Base
  belongs_to :user
  belongs_to :share#, counter_cache: true

  def Interaction.get_interaction(user_id, share_id)
    interaction = Interaction.find_by user_id: user_id, share_id: share_id
    interaction = Interaction.new({user_id: user_id, share_id: share_id}) unless interaction
  end
end
