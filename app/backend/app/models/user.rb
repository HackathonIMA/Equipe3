class User < ActiveRecord::Base
  has_many :shares
  has_many :interactions

  def toggle_support!(share_id)
    interaction = Interaction.get_interaction(self.id, share_id)
    interaction.support |= false # default
    interaction.support = !interaction.support
    interaction.save()
  end
end
